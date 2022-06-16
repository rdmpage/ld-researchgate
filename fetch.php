<?php

//----------------------------------------------------------------------------------------
function create_filename($base_dir, $profile, $extension = 'json')
{
	$filename = '';

	$prefix = substr($profile, 0, 1);
	
	$destination_dir = $base_dir . '/' . $prefix;
	
	if (!file_exists($destination_dir))
	{
		$oldumask = umask(0); 
		mkdir($destination_dir, 0777);
		umask($oldumask);
	}
	
	$filename = $destination_dir . '/' . $profile  . '.' . $extension; 
	
	return $filename;

}

//----------------------------------------------------------------------------------------


$profiles = array(
	'Hanyrol-Ahmad-Sah',
	'Zdenek-Macat',
	'Ulmar-Grafe',
	'Freddy-Bravo-2',
	'David-Salazar-Valenzuela',
	'Ulisses-Caramaschi',
	'Jose-Pombal',
	'Konrad-Mebert',
	'Nicolas-Penafiel',
	'Timothy-Colston',
	'Mario-Yanez-Munoz',
	'Juan-Guayasamin',
	'Omar-Torres-Carvajal',
	'Juan-Carlos-Chaparro',
	'Gerardo-Leynaud',
	'Paola-Carrasco-5',
	'https://www.researchgate.net/profile/Artur-Taszakowski',
	'https://www.researchgate.net/profile/Junggon-Kim',
	'https://www.researchgate.net/profile/Claas-Damken',
	'https://www.researchgate.net/profile/Lukasz-Michalczyk',
	'https://www.researchgate.net/profile/Lukasz-Kaczmarek-3',
	'https://www.researchgate.net/profile/Sandra-Mcinnes-2',
	'https://www.researchgate.net/profile/Lorenzo-Prendini',
	
	'https://www.researchgate.net/profile/Jairo-A-Moreno-Gonzalez',
	'https://www.researchgate.net/profile/Osvaldo-Villarreal-Manzanilla',
);


$chrome = '/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome';


foreach ($profiles as $profile)
{
	$profile = str_replace('https://www.researchgate.net/profile/', '', $profile);

	$url = 'https://www.researchgate.net/profile/' . $profile;
	
	$filename = create_filename(dirname(__FILE__) . '/cache', $profile, 'html');
	
	$output_filename = create_filename(dirname(__FILE__) . '/cache', $profile, 'json');
	
	if (!file_exists($output_filename))
	{
		if (!file_exists($filename))
		{
			$command = $chrome . ' --headless --user-agent="Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.50 Safari/537.36" --dump-dom ' . $url . ' > ' . $filename;
			system($command);		
		}
	
		$html = file_get_contents($filename);
	
		$lines = explode("\n", $html);
	
		//print_r($lines);
	
		foreach ($lines as $line)
		{
			//echo $line . "\n";
			if (preg_match('/<script type="application\/ld\+json">(?<data>.*)<\/script>/Uu', $line, $m))
			{
				echo $m['data'];
				
				if (preg_match('/"@type":"Person"/', $m['data']))
				{
					file_put_contents($output_filename, $m['data']);
				}
			}
		}

	}
	
}

?>
