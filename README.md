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

### 🐳 Docker
- 🚀 Easy setup with Docker Compose
- 🐘 MySQL database container
- 🐳 PHP-FPM container for backend
- 🌐 Nginx container for serving the application
- 📊 phpMyAdmin container for database management

---

## 🚀 Installation
### Prerequisites
Before you begin, ensure you have the following installed:
- [Docker Desktop](https://www.docker.com/products/docker-desktop)

**Step 1: Clone the Repository**
```bash
git clone https://github.com/Joshua31400/Steam-City
cd Steam-City
```

**Step 2: Execute in WSL**
1. Import the database schema:
```bash
docker-compose up -d 
```

**Create/ edit .env with your credentials:**
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

⚠️ **IMPORTANT**: Ne commitez **JAMAIS** le fichier `.env` sur Git! Il contient vos secrets. Seul `.env.example` est partagé.

Puis accédez à:
```
http://localhost:8080
```

Ou avec WAMP/MAMP:
```
http://localhost/Steam-City/public/
```

Architecture:
```

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
![Nginx](https://img.shields.io/badge/Nginx-009639.svg?style=for-the-badge&logo=nginx&logoColor=white)

### Tools & Services
![Docker](https://img.shields.io/badge/Docker-2496ED.svg?style=for-the-badge&logo=docker&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032.svg?style=for-the-badge&logo=git&logoColor=white)
![OAuth2](https://img.shields.io/badge/OAuth2-3C873A.svg?style=for-the-badge&logo=auth0&logoColor=white)

---

## 👥 Fichiers à partager/ne pas partager

### ✅ À POUSSER sur Git (fichiers du projet)
- `README.md` - Documentation
- `APACHE_SETUP.md` - Configuration Apache
- `.env.example` - Template des variables d'environnement
- `sql/database.sql` - Schéma de base de données
- `public/router.php` - Routeur pour serveur PHP intégré
- `public/pages/*.php` - Pages du site
- `public/style/*.css` - Feuilles de style
- `internal/` - Dossier complet (controllers, models, routes, helpers)
- `config/constants.php`, `config/database.php`, `config/oauth.php` - Configuration

### ❌ NE PAS POUSSER sur Git (fichiers locaux/sensibles)
- **`.env`** - Contient les secrets OAuth - ⚠️ JAMAIS sur Git!
- **`php.ini`** - Configuration PHP locale (chemin différent par développeur)
- **`vendor/`** - Dépendances Composer (généré automatiquement)
- **`.idea/`** - Configuration IDE locale
- **Fichiers temporaires** - Cache, logs, etc.

### 📝 Comment gérer .env.example vs .env

1. **`.env.example`** - Partagé sur Git (sans valeurs secrètes)
2. **`.env`** - Chaque développeur crée son propre (à partir de .env.example)
3. **`.gitignore`** - Assure que `.env` n'est jamais commité

---

## Team

Project realized by:

- **Sebastien DELVER** - [@DantesDels](https://github.com/DantesDels)
- **Joshua BUDGEN** - [@joshua31400](https://github.com/joshua31400)

*Ynov Campus Toulouse - 2026*