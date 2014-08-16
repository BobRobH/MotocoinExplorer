(readme is outdated)

MotocoinExplorer

	Block chain explorer for Motocoin cryptocurrency(http://motocoin.org). 
	Based on https://github.com/CallMeJake/BlockCrawler
	Online demo: http://moto-explorer.fvds.ru
	
	
Dependencies:
	- web server with php & curl
	- `motocoind` server (see http://motocoin.org)
	

MotocoinExplorer installation:

	1. Copy all files from `src` dir to your web server `public_html` dir.
	2. Install & configure `motocoind` server. You need to set the `txindex=1` configuration option to be able to look up historical transactions.
	3. Open config/motodConfig.php & change `motocoind` server's settings:
	
			$GLOBALS["wallet_ip"] = "127.0.0.1";
			$GLOBALS["wallet_port"] = "8332";
			$GLOBALS["wallet_user"] = "username";
			$GLOBALS["wallet_pass"] = "password";	
			
		Here are some sample entries for the value $GLOBALS["wallet_ip"]:
		
		"127.0.0.1" - This will communitcate with the daemon in clear text
		"http://127.0.0.1" - This is also an unencrypted connection
		"https://127.0.0.1" - This will connect to the wallet using SSL encryption.			
		
	4. Done! Go to `your.domain`/index.php


MotocoinExplorer files:

	src/classes/* - Files for working with motocoin network
	src/config/*  - Configuration files
	src/parts/*   - files which included(using include 'file';) in another pages
	src/*         - public site's pages
	src/index.php - the home page 
	
License

MotocoinExplorer is released under the terms of the MIT license. 
See COPYING for more information or see http://opensource.org/licenses/MIT.
