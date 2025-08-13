# Cinephoria - Déploiement avec Docker

Ce projet peut être facilement exécuté avec Docker en quelques étapes simples.

## Prérequis

- Docker installé sur votre machine
- Git (pour cloner le dépôt)

## Instructions d'installation

1. **Cloner le dépôt** :
   ```bash
   git clone [URL_DU_DEPOT]
   cd cinephoria-main
   ```

2. **Construire l'image Docker** :
   ```bash
   docker build -t cinephoria .
   ```

3. **Lancer le conteneur** :
   ```bash
   docker run -d -p 8080:80 --name cinephoria-app cinephoria
   ```

4. **Accéder à l'application** :
   - Application : http://localhost:8080
   - phpMyAdmin : http://localhost:8080/phpmyadmin (si activé)

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
