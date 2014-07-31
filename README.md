Anarchy-Online GMI Proxy
========================

Run your own copy of the Anarchy Online GMI.

Requirements
---
 - Apache, PHP, 
 - Optional: MySQL server

Instalation
---
1. Clone the project into your web server. 
2. Copy includes/config.php.example to includes/config.php and configure as needed.
3. **(Optional)** Import the database structure in install/install.sql
4. Configure apache to use the NameVirtualHost in install/httpd.conf
5. On the client computer, change the HOSTS file to redirect aomarket.funcom.com to your server.

Notes
---
- Changes can be made to the code to return to the Anarchy Online client by adding files to the /overrides/ directory. Some basic changes has been included.
- **This project was never completed to my satisfaction, and it's very possible that it can pose a security risk for your web server**. Review the code and only use it if you understand what it's doing and any implications for running it.
