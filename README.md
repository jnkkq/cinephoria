# Cinephoria - Plateforme Cinéma (Web, Mobile, Bureautique)

Cinephoria est une solution complète de gestion de cinéma, incluant :
- Une application web (PHP/MySQL)
- Une application mobile (Flutter)
- Une application bureautique (Tkinter Python)
- Un backend API REST (PHP)

---

## SOMMAIRE
- [Fonctionnalités](#fonctionnalités)
- [Structure du projet](#structure-du-projet)
- [1. Installation de l'application web](#1-installation-de-lapplication-web)
- [2. Installation de l'application mobile](#2-installation-de-lapplication-mobile)
- [3. Installation de l'application bureautique](#3-installation-de-lapplication-bureautique)
- [4. API REST Backend](#4-api-rest-backend)
- [Base de données](#base-de-données)
- [Déploiement](#déploiement)
- [Auteurs](#auteurs)

---

## Fonctionnalités
- Gestion des films, séances, salles, réservations et utilisateurs
- Tableau de bord avancé (statistiques, graphiques)
- API REST pour accès mobile/bureautique
- Application mobile multiplateforme (Flutter)
- Application bureautique (Tkinter Python)

---

## Structure du projet

```
cinephoria-master/
│
├── api/                 # Backend API REST (PHP)
├── cinema_mobile/       # Application mobile Flutter
├── config/              # Fichiers de configuration PHP
├── controllers/         # Contrôleurs PHP (web)
├── models/              # Modèles PHP
├── public/              # Racine web (Apache/Nginx)
├── sql/                 # Scripts SQL pour la base de données
├── tkinter_app/         # Application bureautique (Python)
├── vendor/              # Librairies PHP (Composer)
├── views/               # Vues PHP (web)
├── composer.json        # Dépendances PHP
├── README.md            # Ce fichier
└── ...
```

---

## 1. Installation de l'application web (PHP)

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Composer
- Serveur web (Apache recommandé)

### Étapes
1. **Cloner le dépôt**
   ```bash
   git clone <repo_url>
   cd cinephoria-master
   ```
2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```
3. **Configurer la base de données**
   - Copier `config/database.php.example` en `config/database.php` (ou éditer le fichier existant)
   - Renseigner vos identifiants MySQL

4. **Importer la base de données**
   - Ouvrir phpMyAdmin ou le terminal MySQL
   - Importer le fichier `sql/cinephoria.sql`

5. **Configurer le serveur web**
   - Pointer le VirtualHost Apache/Nginx vers le dossier `public/`
   - Exemple Apache :
     ```apache
     DocumentRoot "/chemin/vers/cinephoria-master/public"
     <Directory "/chemin/vers/cinephoria-master/public">
         AllowOverride All
         Require all granted
     </Directory>
     ```

6. **Lancer l'application**
   - Accéder à `http://localhost` ou l'URL configurée

---

## 2. Installation de l'application mobile (Flutter)

### Prérequis
- [Flutter SDK](https://docs.flutter.dev/get-started/install)
- Android Studio ou VSCode (avec extensions Flutter/Dart)

### Étapes
1. **Aller dans le dossier mobile**
   ```bash
   cd cinema_mobile
   ```
2. **Installer les dépendances**
   ```bash
   flutter pub get
   ```
3. **Lancer l'application**
   - Sur un émulateur ou un appareil connecté :
     ```bash
     flutter run
     ```

### Configuration API
- L'application mobile communique avec l'API REST du backend (`/api/`).
- Adapter l'URL de l'API dans le code Flutter si besoin (voir `lib/` dans le projet Flutter).

---

## 3. Installation de l'application bureautique (Tkinter)

### Prérequis
- Python 3.8 ou supérieur
- pip
- Tkinter (`pip install tk` ou inclus selon la distribution)

### Étapes
1. **Aller dans le dossier bureautique**
   ```bash
   cd tkinter_app
   ```
2. **Installer les dépendances** (si un requirements.txt existe)
   ```bash
   pip install -r requirements.txt
   ```
3. **Lancer l'application**
   ```bash
   python main.py
   ```

### Configuration API
- L'application bureautique consomme aussi l'API REST PHP.
- Adapter l'URL de l'API dans le code Python si besoin.

---

## 4. API REST Backend (PHP)

- Les endpoints API sont dans `api/`
- Exemples :
  - `api/reservations/derniers_jours.php` : statistiques réservations pour dashboard
  - `api/auth/` : authentification
  - `api/films/` : gestion des films
- Les routes sont accessibles via HTTP (GET/POST)
- Voir chaque fichier pour les paramètres attendus

---

## Base de données
- Script SQL : `sql/cinephoria.sql`
- Tables principales : utilisateurs, films, genres, salles, réservations, etc.
- Adapter les accès MySQL dans `config/database.php`

Exemple transaction : 

START TRANSACTION;

INSERT INTO utilisateurs (email, mot_de_passe, nom, prenom, username, role, confirme) VALUES
('alice@email.com', 'hash1', 'Alice', 'Dupont', 'aliceD', 'utilisateur', TRUE),
('bob@email.com', 'hash2', 'Bob', 'Martin', 'bobM', 'employe', TRUE);

INSERT INTO cinemas (nom, adresse, telephone, horaires) VALUES
('Cinéma Lumière', '12 rue des Fleurs, Paris', '0102030405', '10:00-23:00'),
('Cinéma Etoile', '5 avenue du Cinéma, Lyon', '0607080910', '12:00-00:00');

COMMIT;

---

## Déploiement
- Pour la production, utiliser un serveur Apache/Nginx sécurisé
- Protéger le dossier `/config` et `/sql` en production
- Configurer les variables d'environnement pour les accès sensibles
- Pour le mobile, générer un APK/iOS via Flutter (`flutter build apk`)
- Pour la bureautique, packager avec PyInstaller si besoin

---

## Auteurs
- jnkkq
