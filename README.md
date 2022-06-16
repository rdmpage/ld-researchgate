# ResearchGate as linked data

ResearchGate web pages have linked data embedded using http://schema.org terms. For example:

```json
{"@context":"http://schema.org","@graph":[{"@type":"Person","name":"David Salazar-Valenzuela","image":{"@type":"ImageObject","contentUrl":"https://i1.rgstatic.net/ii/profile.image/278687002972188-1443455567690_Q128/David-Salazar-Valenzuela.jpg"},"mainEntityOfPage":"https://www.researchgate.net/profile/David-Salazar-Valenzuela","url":"https://www.researchgate.net/profile/David-Salazar-Valenzuela","sameAs":["https://orcid.org/0000-0002-3874-7690","http://www.wikidata.org/entity/Q44447731","https://www.researchgate.net/profile/David-Salazar-Valenzuela"],"jobTitle":"Professor (Assistant)","description":"David Salazar-Valenzuela works at BioCamb (Centro de Investigación de la Biodiversidad y Cambio Climático), Universidad Tecnológica Indoamérica.","affiliation":{"@type":"Organization","name":"Universidad Tecnológica Indoamérica","url":"https://www.researchgate.net/institution/Universidad-Tecnologica-Indoamerica"}},{"@type":"WebPage","url":"https://www.researchgate.net/profile/David-Salazar-Valenzuela","reviewedBy":{"@type":"Person","name":"David Salazar-Valenzuela"}}]}
```

Note in this example the `sameAs` relation to ORCID and Wikidata!



