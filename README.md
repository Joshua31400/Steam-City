# Steam City
> This project is a web application that allows users to view and manage their games. Looks like a steam clone, but it's not trust us.

• [Features](#-features)  
• [Installation](#-installation)  
• [Technologies](#-technologies)   
• [Team](#team)  

---

## 📖 About
Steam City is a full-stack web application inspired by Steam, designed to help gamers manage their personal game libraries. Built with modern web technologies, it provides a seamless experience for discovering games, tracking achievements, and managing your gaming profile.

*Note: This is an educational project and not affiliated with Valve Corporation or Steam.*

## ✨ Features
### 👤 User Features
- 📚 Build and manage your personal game library  
- 🏆 Track achievements and gaming milestones  
- ⏱️ Monitor playtime and game statistics
- 👤 Customize your user profile

### 🛡️ Admin Features
- 🎮 Full CRUD operations for games (Create, Read, Update, Delete)
- 👥 Complete user management system
- 📊 View platform statistics and analytics
- 🔐 Role-based access control

### 🔐 Authentications
- 📧 Traditional email/password login
- <img src="https://img.shields.io/badge/Google-4285F4?style=flat&logo=google&logoColor=white" alt="Google" height="20"/> OAuth 2.0 integration
- <img src="https://img.shields.io/badge/GitHub-181717?style=flat&logo=github&logoColor=white" alt="GitHub" height="20"/> OAuth 2.0 integration
- 🔒 Secure password hashing (bcrypt)
- 🛡️ Protected routes with middleware

---

## 🚀 Installation
### Prerequisites
Before you begin, ensure you have the following installed:
- [PHP 7.4+](https://www.php.net/downloads)
- [MySQL 5.7+](https://dev.mysql.com/downloads/mysql/)
- [WAMP Server](https://www.wampserver.com/en/)(Windows) or [XAMPP](https://www.apachefriends.org/index.html)(Cross-platform)

**Step 1: Clone the Repository**
```bash
git clone https://github.com/Joshua31400/Steam-City
cd Steam-City
```

**Step 2: Database Setup**
1. Import the database schema:
```bash
mysql -u root -p steam_city < sql/database.sql
```

2. Or manually import via phpMyAdmin:
- Open phpMyAdmin
- Create a new database named steam_city
- Import the sql/database.sql file

**Step 3: Environment Configuration**
1. Copy the environment template:
```bash
cp .env.example .env
```

2. Edit .env with your credentials:
```bash
# Database Configuration
   DB_HOST=localhost
   DB_NAME=steam_city
   DB_USER=root
   DB_PASS=your_password

   # Application
   APP_URL=http://localhost:8080

   # Google OAuth (optional)
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   GOOGLE_REDIRECT_URI=http://localhost:8080/auth/google/callback

   # GitHub OAuth (optional)
   GITHUB_CLIENT_ID=your_github_client_id
   GITHUB_CLIENT_SECRET=your_github_client_secret
   GITHUB_REDIRECT_URI=http://localhost:8080/auth/github/callback
```
**Step 5: Launch the Application**  
Using PHP's built-in server:
```bash
php -S localhost:8000 -t public public/index.php
```

Or use WAMP/MAMP and access via:
```
http://localhost/Steam-City/public/
```

Architecture:
```
Steam-City
    │   README.md
    │   
    ├───.idea
    │       .gitignore
    │       
    ├───config
    │       constants.php
    │       database.php
    │       oauth.php
    │       
    ├───internal
    │   ├───auth
    │   │       GithubProvider.php
    │   │       GoogleProvider.php
    │   │       OAuthProvider.php
    │   │       
    │   ├───controllers
    │   │       AdminController.php
    │   │       AuthController.php
    │   │       GameController.php
    │   │       OAuthController.php
    │   │       ProfileController.php
    │   │       
    │   ├───helpers
    │   │       functions.php
    │   │       validation.php
    │   │       
    │   ├───middleware
    │   │       AuthMiddleware.php
    │   │       
    │   ├───models
    │   │       Achievement.php
    │   │       Game.php
    │   │       User.php
    │   │       UserGame.php
    │   │       
    │   └───routes
    │           routes.php
    │           
    ├───public
    │   │   .htaccess
    │   │   index.php
    │   │   
    │   ├───assets
    │   │   └───js
    │   │           admin-modal.js
    │   │           
    │   └───pages
    │           admin.php
    │           home.php
    │           login.html
    │           profile.php
    │           sign-in.html
    │           
    └───sql
            database.sql
```
---

## 💻 Technologies

### Frontend
![HTML5](https://img.shields.io/badge/HTML5-E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E.svg?style=for-the-badge&logo=javascript&logoColor=black)

### Backend
![PHP](https://img.shields.io/badge/PHP-777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)

### Tools & Services
![WAMP](https://img.shields.io/badge/WAMP-F70000.svg?style=for-the-badge&logo=wampserver&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032.svg?style=for-the-badge&logo=git&logoColor=white)
![OAuth2](https://img.shields.io/badge/OAuth2-3C873A.svg?style=for-the-badge&logo=auth0&logoColor=white)

---

## Team

Project realized by:

- **Sebastien DELVER** - [@DantesDels](https://github.com/DantesDels)
- **Joshua BUDGEN** - [@joshua31400](https://github.com/joshua31400)

*Ynov Campus Toulouse - 2026*