<div id="top"></div>
<!-- PROJECT LOGO -->
<br />

<div align="center">
  <a href="https://github.com/fajar-subhan/WebChat">
    <img src="https://github.com/fajar-subhan/WebChat/blob/master/assets/images/icons/logo.png" alt="Logo" width="80" height="80">
  </a>

<h3 align="center">Web Chat</h3>

  <p align="center">
    Open Source Web Chat Application
  </p>
</div>


<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-web-chat">About The Web Chat</a>
      <ul>
        <li><a href="#server-requirements">Server Requirements</a></li>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#license">License</a></li>
    <li><a href="#author">Author</a></li>
  </ol>
</details>



<!-- ABOUT THE Web Chat -->
## About The Web Chat
Webchat is a format that allows customers to communicate directly with them online, often on their website and in real time. The web chat window appears as an overlay of website pages in the browser, allowing users to type messages directly into text fields, and often attach images and other files as well.
<p align="right">(<a href="#top">back to top</a>)</p>

## Server Requirements
PHP version 5.6 or newer is recommended.

It should work on 5.4.8 as well, but we strongly advise you NOT to run such old versions of PHP, because of potential security and performance issues, as well as missing features.

### Built With

* [PHP](https://www.php.net/)
* [JAVASCRIPT](https://www.javascript.com/)
* [FS Framework](https://github.com/fajar-subhan/FS_Framework)

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- GETTING STARTED -->
## Getting Started

Before starting we prepare a few things first such as config composer, database and others

### Installation

1. Clone the repo web chat
   ```sh
   git clone https://github.com/fajar-subhan/WebChat.git
   ```
2. Install Composer
   ```sh
   composer install
   ```
3. Import chat.sql file into database
4. Rename the .env.example file to .env only
5. Rename the file app/helpers/Security_example.ini to Security.ini only and input some parameters
```sh
  encription_key  = "The key of this encryption is secret";
  encription_iv   = "A non-NULL Initialization Vector";
  encription_algo = "The cipher method. For a list of available cipher methods";
```

6. Please fill in the .env file as follows
  ```sh
    DB_DSN  = mysql:host=localhost;dbname=database_name;port:database_port
    DB_USER = username
    DB_PASS = password
  ```
7. Define the controller in the config/config.php file
 ```sh
    if(!defined('CONTROLLER')) define('CONTROLLER','Controller Name');
  ```
8. Create a files folder in the app/assets/ folder
9. Create a chat folder in the app/assets/images/ folder
10. Happy Coding :)

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- LICENSE -->
## License

Distributed under the MIT License.

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- Author -->
## Author

Fajar Subhan - fajarsubhan9b@gmail.com

Project Link: [Web Chat](https://github.com/fajar-subhan/WebChat)

<p align="right">(<a href="#top">back to top</a>)</p>


