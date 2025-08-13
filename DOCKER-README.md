# Cinephoria - Déploiement avec Docker

Ce projet peut être facilement exécuté avec Docker en quelques étapes simples. Deux options sont disponibles :

## Option 1 : Utiliser l'image Docker pré-construite (recommandé)

```bash
# Télécharger l'image
docker pull ghcr.io/jnkkq/cinephoria:latest

# Lancer le conteneur
docker run -d -p 80:80 -p 3306:3306 --name cinephoria-app ghcr.io/jnkkq/cinephoria:latest
```

**Accès :**
- Application : http://localhost
- phpMyAdmin : http://localhost:8080
  - Utilisateur : `root`
  - Mot de passe : `root`

## Option 2 : Construire l'image localement

### Prérequis
- Docker installé sur votre machine
- Git (pour cloner le dépôt)

### Instructions d'installation

1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/jnkkq/cinephoria.git
   cd cinephoria
   ```

2. **Construire l'image Docker** :
   ```bash
   docker build -t cinephoria .
   ```

3. **Lancer le conteneur** :
   ```bash
   docker run -d -p 80:80 -p 3306:3306 --name cinephoria-app cinephoria
   ```

4. **Accéder à l'application** :
   - Application : http://localhost
   - phpMyAdmin : http://localhost:8080
     - Utilisateur : `root`
     - Mot de passe : `root`

## Configuration

- Le serveur MySQL est configuré avec les identifiants par défaut :
  - Utilisateur : root
  - Mot de passe : (vide)
  - Base de données : cinephoria

## Importer une base de données

Pour importer une base de données, placez votre fichier SQL dans le dossier `sql/` et nommez-le `cinephoria.sql`. Il sera automatiquement importé au démarrage du conteneur.

## Arrêter l'application

Pour arrêter l'application :
```bash
docker stop cinephoria-app
```

Pour redémarrer :
```bash
docker start cinephoria-app
```
## Dépannage

Si vous rencontrez des problèmes :
1. Vérifiez les logs : `docker logs cinephoria-app`
2. Assurez-vous que le port 8080 est disponible
3. Vérifiez que Docker est en cours d'exécution
