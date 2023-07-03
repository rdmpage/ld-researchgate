<?php

// For each source file generate corresponding triples file

// php -d memory_limit=-1 triples.php

error_reporting(E_ALL);


require_once(dirname(__FILE__) . '/vendor/autoload.php');

use ML\JsonLD\JsonLD;
use ML\JsonLD\NQuads;

$cuid = new EndyJasmi\Cuid;

$config['cache'] = dirname(__FILE__) . '/cache';


//----------------------------------------------------------------------------------------
// Fix any issues we might have with triples
function fix_triples($triples)
{
	global $cuid;
	
	$lines = explode("\n", $triples);
	
	// print_r($lines);	
	
	// fix bad URIs
	foreach ($lines as &$line)
	{
		//echo $line . "\n";
		if (preg_match_all('/\<(?<uri>(https?|URI:\s+).*)\>\s/U', $line, $m))
		{
			foreach ($m['uri'] as $original_uri)
			{				
				$uri = $original_uri;
				
				$uri = str_replace('<', '%3C', $uri);
				$uri = str_replace('>', '%3E', $uri);

				$uri = str_replace('[', '%5B', $uri);
				$uri = str_replace(']', '%5D', $uri);
			
				$uri = str_replace(' ', '%20', $uri);	
				$uri = str_replace('"', '%22', $uri);	
							
				$uri = str_replace('{\_}', '', $uri);
				$uri = str_replace('\_', '', $uri);
				
				$uri = str_replace('}', '', $uri);	
				$uri = str_replace('{', '', $uri);					
				
				$uri = preg_replace('/URI:\s+/', '', $uri);	

				$uri = preg_replace('/%x/', '', $uri);	
				
				$uri = preg_replace('/\x91/', ' ', $uri);
					
				$line = str_replace('<' . $original_uri . '>', '<' . $uri . '>', $line);
			}
		}
	}
	
	
	// b-nodes
	$bnodes = array();
	
	// build list of b-nodes
	foreach ($lines as &$line)
	{
		if (preg_match('/^(?<id>_:b\d+)/', $line, $m))
		{
			if (!isset($bnodes[$m['id']]))
			{
				$bnodes[$m['id']] = '_:' . $cuid->cuid();
			}
		}
		if (preg_match('/(?<id>_:b\d+)\s+\.\s*$/', $line, $m))
		{
			//print_r($m);
			if (!isset($bnodes[$m['id']]))
			{
				$bnodes[$m['id']] = '_:' . $cuid->cuid();
			}
		}		
	}
	
	// print_r($bnodes);
	
	foreach ($lines as &$line)
	{
		if (preg_match('/^(?<id>_:b\d+)/', $line, $m))
		{
			$line = preg_replace('/^(_:b\d+)/', $bnodes[$m['id']], $line);
		}
		
		if (preg_match('/(?<id>_:b\d+)\s+\.\s*$/', $line , $m))
		{
			$line = preg_replace('/(_:b\d+)\s+\.\s*$/', $bnodes[$m['id']]. " . ", $line);
		}		
	}
	
	$new_triples = join("\n", $lines);
	$new_triples .= "\n"; 
	
	return $new_triples;

}

//----------------------------------------------------------------------------------------


$force = false;
$force = true;


$files1 = scandir($config['cache']);

$nquads = new NQuads();

foreach ($files1 as $directory)
{
	if (preg_match('/^[A-Z]$/', $directory))
	{	
		$files2 = scandir($config['cache'] . '/' . $directory);
		
		foreach ($files2 as $filename)
		{
			if (preg_match('/\.json$/', $filename))
			{
				$id = str_replace('.json', '', $filename);				
				$json = file_get_contents($config['cache'] . '/' . $directory . '/' . $filename);
				
				$json = str_replace('"@context":"http://schema.org"', '"@context": {"@vocab":"http://schema.org/"}', $json);
				
				$output = $config['cache'] . '/' . $directory . '/' . $id . '.nt';
				
				if (!file_exists($output) || $force)
				{				
					echo $id . "\n";
					$quads = JsonLD::toRdf($json);
					$serialized = $nquads->serialize($quads);
					$serialized = fix_triples($serialized);
					file_put_contents($output, $serialized);
				}
				else
				{
					echo "$id done\n";
				}
			}
		}
	}
}

?>


