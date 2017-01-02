<?php

// .EU
$additionaldomainfields[".eu"] = array();
$additionaldomainfields[".eu"][] = array("DisplayName" => "Registrant country", "Name" => "euregistrantcountry", "Type" => "dropdown", "Options" => "AT|Austria,BE|Belgium,BG|Bulgaria,CZ|Czech Republic,CY|Cyprus,DE|Germany,DK|Denmark,ES|Spain,EE|Estonia,FI|Finland,FR|France,GR|Greece,GB|Great-Britain,HR|Croatia,HU|Hungary,IE|Ireland,IT|Italy,LT|Lithuania,LU|Luxembourg,LV|Latvia,MT|Malta,NL|Metherlands,PL|Poland,PT|Portugal,RO|Romania,SE|Sweden,SK|Slovak Republic,SI|Republic of Slovenia,AX|Aland Islands,GF|French Guiana,GI|Gibraltar,GP|Guadeloupe,MQ|Martinique,RE|Reunion Island,IS|Iceland,LI|Lichtenstein,NO|Norway", "Default" => "");

// .FR
$additionaldomainfields[".fr"] = array();
$additionaldomainfields[".fr"][] = array("DisplayName" => "Birth date", "Name" => "frbirthdate", "Type" => "text", "Size" => "20", "Default" => "DD/MM/YYYY", "Required" => true);
$additionaldomainfields[".fr"][] = array("DisplayName" => "Technical contact organization", "Name" => "frtechorg", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);


// .CO.UK
$additionaldomainfields[".co.uk"] = array();
$additionaldomainfields[".co.uk"][] = array("DisplayName" => "Company type", "Name" => "ukcompanytype", "Type" => "dropdown", "Options" => "LTD|Limited Company,PLC|Public Limited Company,PTNR|Partnership,STRA|Sole Trader,LLP|Limited Liability Partnership,IP|Industrial - Provident Registered Company,IND|Individual - representing self,SCH|School,RCHAR|Registered Charity,GOV|Government Body,CRC|Corporation by Royal Charter,STAT|Statutory Body,OTHER|Entity that does not fit into any of the above,FIND|Non-UK Individual - representing self,FCORP|Non-UK Corporation,FOTHER|Non-UK Entity that does not fit into any of the above", "Default" => "");
$additionaldomainfields[".co.uk"][] = array("DisplayName" => "Company registration number", "Name" => "ukcompanyregistrationnumber", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".co.uk"][] = array("DisplayName" => "Registrant trading name", "Name" => "ukregistranttradingname", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);


// .US
$additionaldomainfields[".us"] = array();
$additionaldomainfields[".us"][] = array("DisplayName" => "Registration purpose", "Name" => "usregistrationpurpose", "Type" => "dropdown", "Options" => "P1|Business use for profit,P2|Non-profit business,P3|Personal Use,P4|Education purposes,P5|Government purposes", "Default" => "");
$additionaldomainfields[".us"][] = array("DisplayName" => "Nexus category", "Name" => "usnexuscategory", "Type" => "dropdown", "Options" => "C11|United States citizen,C12|Permanent resident of the United States,C21|A United States Organization,C31|Engages in lawful activities,C32|Has an office or other facility in the United States", "Default" => "");

// .ES
$additionaldomainfields[".es"] = array();
$additionaldomainfields[".es"][] = array("DisplayName" => "Identity type", "Name" => "esidtype", "Type" => "dropdown", "Options" => "0|Generic ID (Not Spanish),1|Spanish national personal ID/ company VAT numbers,3|Spanish resident alien ID Number", "Default" => "");
$additionaldomainfields[".es"][] = array("DisplayName" => "Identity number", "Name" => "esidnumber", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".es"][] = array("DisplayName" => "Contact type", "Name" => "escontacttype", "Type" => "dropdown", "Options" => "1|Person,39|Economic Interest Grouping,47|Association,59|Sports Association,68|Trade Association,124|Savings Bank,150|Community Property,152|Condominium,164|Religious Order or Institution,181|Consulate,197|Public Law Association,203|Embassy,229|Municipality,269|Sports Federation,286|Foundation,365|Mutual Insurance Company,434|Provincial Government Body,436|National Government Body,439|Political Party,476|Trade Union,510|Farm Partnership,524|Public Limited Company / Corporation,525|Sports Public Limited Company,554|Partnership,560|General Partnership,562|Limited Partnership,566|Cooperative,608|Worker-owned Company,612|Limited Liability Company,713|Spanish (company) Branch,717|Temporary Consortium / Joint Venture,744|Worker-owned Limited Company,745|Provincial Government Entity,746|National Government Entity,747|Local Government Entity,877|Others,878|Designation of Origin Regulatory Council,879|Natural Area Management Entity", "Default" => "");

// .NL
$additionaldomainfields[".nl"] = array();
$additionaldomainfields[".nl"][] = array("DisplayName" => "Legal type", "Name" => "nllegaltype", "Type" => "dropdown", "Options" => "ANDERS|Other,BGG|Non-Dutch EC company,BRO|Non-Dutch legal form/enterprise/subsidiary,BV|Limited company,BVI|Limited company in formation,COOP|Cooperative,CV|Limited partnership,EENMANSZAAK|Sole trader,EESV|European economic interest group,KERK|Religious society,MAATSCHAP|Partnership,NV|Public company,OWM|Mutual benefit company,PERSOON|Natural person,REDR|Shipping company,STICHTING|Foundation,VERENIGING|Association,VOF|Trading partnership", "Default" => "");

// .PRO
$additionaldomainfields[".pro"] = array();
$additionaldomainfields[".pro"][] = array("DisplayName" => "Profession", "Name" => "proprofession", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);

// .IT
$additionaldomainfields[".it"] = array();
$additionaldomainfields[".it"][] = array("DisplayName" => "Nationality", "Name" => "itnationality", "Type" => "dropdown", "Options" => "AT|Austria,BE|Belgium,BG|Bulgaria,CY|Cyprus,CZ|Czech Republic,DK|Denmark,EE|Estonia,FI|Finland,FR|France,DE|Germany,GR|Greece,HU|Hungary,IE|Ireland,IT|Italy,LV|Latvia,LT|Lithuania,LU|Luxembourg,MT|Malta,NL|Netherlands,PL|Poland,PT|Portugal,RO|Romania,SK|Slovakia,SI|Slovenia,ES|Spain,SE|Sweden,GB|United Kingdom", "Default" => "");
$additionaldomainfields[".it"][] = array("DisplayName" => "Entry type", "Name" => "itentrytype", "Type" => "dropdown", "Options" => "1|Italian and foreign natural persons,2|Companies/one man companies,3|Freelance workers/professionals,4|Non-profit organisations,5|Public organisations,6|Other subjects,7|Foreigners", "Default" => "");
$additionaldomainfields[".it"][] = array("DisplayName" => "Registration code", "Name" => "itregistrationcode", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);

// .DE
$additionaldomainfields[".de"] = array();
$additionaldomainfields[".de"][] = array("DisplayName" => "Use proxy admin", "Name" => "deproxyadmin", "Type" => "tickbox", "Description" => "Tick if you want us to set a proxy admin contact, or fill in your own admin info");
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin name", "Name" => "a_name", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin street", "Name" => "a_street", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin city", "Name" => "a_city", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin country", "Name" => "a_country", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin phone", "Name" => "a_phone", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin email", "Name" => "a_email", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin postal code", "Name" => "a_pc", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
$additionaldomainfields[".de"][] = array("DisplayName" => "Admin state/province", "Name" => "a_sp", "Type" => "text", "Size" => "20", "Default" => "", "Required" => true);
