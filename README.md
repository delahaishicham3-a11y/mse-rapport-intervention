# MSE - Rapport d'Intervention

Application professionnelle de gestion des rapports d'intervention pour la maintenance des systèmes énergétiques.

![MSE Banner](https://img.shields.io/badge/MSE-Maintenance-%23667eea?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13-336791?style=for-the-badge&logo=postgresql)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker)

## 🌟 Fonctionnalités

- ✅ **Création de rapports** d'intervention détaillés
- 📊 **Saisie des mesures** MIN/MAX pour 2 chaudières
- 🔧 **Contrôles et relevés** complets
- 📝 **Observations** et recommandations
- 📄 **Génération PDF** professionnelle avec TCPDF
- 💾 **Sauvegarde persistante** avec PostgreSQL
- 🔍 **Recherche** et filtrage des rapports
- 📱 **Interface responsive** optimisée mobile/desktop

## 🚀 Installation Locale

### Prérequis

- PHP 8.0 ou supérieur
- PostgreSQL 12 ou supérieur
- Composer
- Apache ou Nginx

### Étapes d'installation

1. **Cloner le repository**

```bash
git clone https://github.com/votre-username/mse-rapport-intervention.git
cd mse-rapport-intervention
```

2. **Installer les dépendances**

```bash
composer install
```

3. **Configurer la base de données**

```bash
# Créer la base de données PostgreSQL
createdb mse_reports

# Copier le fichier d'environnement
cp .env.example .env

# Éditer .env avec vos paramètres PostgreSQL
nano .env
```

4. **Créer les dossiers nécessaires**

```bash
mkdir -p uploads reports
chmod 777 uploads reports
```

5. **Démarrer l'application**

```bash
# Avec PHP built-in server (développement)
cd public
php -S localhost:8000

# Ou configurer Apache/Nginx pour pointer vers /public
```

6. **Accéder à l'application**

Ouvrez votre navigateur : `http://localhost:8000`

## 🐳 Déploiement avec Docker

### Build et run local

```bash
# Build l'image
docker build -t mse-rapport .

# Run avec PostgreSQL
docker run -d \
  -p 8080:80 \
  -e DATABASE_URL="postgres://user:password@host:5432/dbname" \
  mse-rapport
```

### Docker Compose

Créez un fichier `docker-compose.yml` :

```yaml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "8080:80"
    environment:
      - DATABASE_URL=postgres://mse:password@db:5432/mse_reports
    depends_on:
      - db
    volumes:
      - ./uploads:/var/www/html/uploads
      - ./reports:/var/www/html/reports

  db:
    image: postgres:15-alpine
    environment:
      - POSTGRES_DB=mse_reports
      - POSTGRES_USER=mse
      - POSTGRES_PASSWORD=password
    volumes:
      - postgres_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"

volumes:
  postgres_data:
```

Puis lancez :

```bash
docker-compose up -d
```

## ☁️ Déploiement sur Render

### 1. Préparer le repository

Assurez-vous que tous les fichiers sont commités sur GitHub :

```bash
git add .
git commit -m "Ready for Render deployment"
git push origin main
```

### 2. Créer les services sur Render

#### A. Créer la base de données PostgreSQL

1. Sur [render.com](https://render.com), cliquez sur **"New +"** → **"PostgreSQL"**
2. Configurez :
   - **Name**: `mse-database`
   - **Database**: `mse_reports`
   - **User**: `mse` (auto-généré)
   - **Region**: `Frankfurt (EU Central)`
   - **Plan**: `Free`
3. Cliquez sur **"Create Database"**
4. Notez l'**Internal Database URL** (sera utilisée automatiquement)

#### B. Créer le Web Service

1. Cliquez sur **"New +"** → **"Web Service"**
2. Connectez votre repository GitHub
3. Configurez :
   - **Name**: `mse-rapport`
   - **Region**: `Frankfurt (EU Central)`
   - **Branch**: `main`
   - **Runtime**: `Docker`
   - **Instance Type**: `Free`
4. Dans **"Advanced"** :
   - Ajoutez la variable d'environnement :
     - **Key**: `DATABASE_URL`
     - **Value**: Sélectionnez votre base de données créée précédemment
5. Cliquez sur **"Create Web Service"**

### 3. Attendre le déploiement

⏱️ Le premier déploiement prend environ 5-10 minutes.

Une fois terminé, votre application sera accessible à :
```
https://mse-rapport.onrender.com
```

### 4. Configuration automatique

Les tables PostgreSQL sont créées automatiquement au premier lancement grâce à `config/database.php`.

## 📊 Structure du Projet

```
mse-rapport-intervention/
├── public/                 # Dossier public (point d'entrée)
│   ├── index.php          # Application principale
│   ├── generate_pdf.php   # Générateur de PDF
│   └── .htaccess          # Configuration Apache
├── src/                   # Classes PHP
│   └── Report.php         # Modèle de rapport
├── config/                # Configuration
│   └── database.php       # Connexion PostgreSQL
├── vendor/                # Dépendances Composer (généré)
├── uploads/               # Fichiers uploadés (si nécessaire)
├── reports/               # Rapports temporaires
├── Dockerfile             # Configuration Docker
├── composer.json          # Dépendances PHP
├── .gitignore            # Fichiers à ignorer
├── .env.example          # Exemple de configuration
└── README.md             # Ce fichier
```

## 🛠️ Technologies Utilisées

- **Backend**: PHP 8.2
- **Base de données**: PostgreSQL 13+
- **Génération PDF**: TCPDF 6.6
- **Frontend**: HTML5, CSS3, JavaScript Vanilla
- **Container**: Docker
- **Serveur Web**: Apache 2.4

## 📝 Utilisation

### Créer un nouveau rapport

1. Remplissez les **Informations Générales** (Onglet Info)
2. Saisissez les **Mesures MIN/MAX** pour les 2 chaudières (Onglet Mesures)
3. Complétez les **Contrôles et Relevés** (Onglet Contrôles)
4. Ajoutez vos **Observations** et recommandations (Onglet Observations)
5. Cliquez sur **💾 Sauvegarder**

### Générer un PDF

1. Remplissez un rapport (ou chargez-en un existant)
2. Cliquez sur **📄 PDF**
3. Le PDF sera téléchargé automatiquement

### Charger un rapport existant

1. Allez dans l'onglet **💾 Sauvés**
2. Cliquez sur **Charger** pour le rapport souhaité
3. Modifiez-le si nécessaire
4. Sauvegardez ou générez un PDF

## 🔧 API de Base de Données

### Méthodes disponibles (src/Report.php)

```php
// Sauvegarder un rapport
$reportModel->save($data);

// Récupérer tous les rapports
$reports = $reportModel->getAll($limit = 100, $offset = 0);

// Récupérer un rapport par ID
$report = $reportModel->getById($id);

// Mettre à jour un rapport
$reportModel->update($id, $data);

// Supprimer un rapport
$reportModel->delete($id);

// Rechercher des rapports
$results = $reportModel->search($query, $limit = 50);

// Compter les rapports
$total = $reportModel->count();
```

## 🔒 Sécurité

- ✅ Protection contre les injections SQL (PDO avec requêtes préparées)
- ✅ Validation des données côté serveur
- ✅ Protection des fichiers sensibles (.htaccess)
- ✅ Headers de sécurité (X-Frame-Options, X-XSS-Protection)
- ✅ Variables d'environnement pour les secrets
- ✅ Sanitization des entrées utilisateur

## 📈 Performances

- **Optimisation des requêtes** : Index sur colonnes fréquemment recherchées
- **Compression GZIP** : Activée via .htaccess
- **Cache navigateur** : Headers Expires configurés
- **Pagination** : Limitation des résultats pour éviter les surcharges

## 🐛 Dépannage

### Problème : Erreur de connexion à la base de données

**Solution** :
```bash
# Vérifiez que PostgreSQL est démarré
sudo systemctl status postgresql

# Vérifiez les paramètres dans .env
cat .env

# Testez la connexion
psql -U mse -d mse_reports -h localhost
```

### Problème : Les tables ne sont pas créées

**Solution** :
```bash
# Les tables sont créées automatiquement, mais vous pouvez forcer :
# Connectez-vous à PostgreSQL et vérifiez :
psql -U mse -d mse_reports

# Dans psql :
\dt  -- Liste les tables
```

### Problème : Génération PDF échoue

**Vérifications** :
- TCPDF est installé : `composer show tecnickcom/tcpdf`
- Permissions d'écriture : `chmod 777 reports/`
- Logs PHP : vérifiez `/var/log/apache2/error.log`

### Problème sur Render : App s'endort

**Solution** :
- Upgrade vers le plan payant (7$/mois) pour éviter le sleep
- Ou utilisez un service de ping externe (UptimeRobot, cron-job.org)

## 🔄 Mises à Jour

Pour mettre à jour l'application déployée sur Render :

```bash
# Faire vos modifications
git add .
git commit -m "Description des modifications"
git push origin main
```

Render redéploiera automatiquement ! 🚀

## 📞 Support

- **Documentation Render** : [docs.render.com](https://docs.render.com)
- **Documentation PostgreSQL** : [postgresql.org/docs](https://www.postgresql.org/docs/)
- **Documentation TCPDF** : [tcpdf.org](https://tcpdf.org)

## 📄 Licence

Propriétaire - MSE © 2024

## 👨‍💻 Auteur

MSE - Maintenance des Systèmes Énergétiques

---

## 🎯 Roadmap

- [ ] Authentification utilisateurs
- [ ] Export Excel des rapports
- [ ] Dashboard avec statistiques
- [ ] Notifications email automatiques
- [ ] API REST pour intégrations
- [ ] Application mobile (PWA)
- [ ] Signatures électroniques
- [ ] Planification des interventions

---

**Fait avec ❤️ pour MSE**