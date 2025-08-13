# Instructions pour tester Cinephoria avec Docker

## Fichiers nécessaires

il y a besoin des fichiers suivants :

```
Cinephoria/
├── .env.example          # Fichier de configuration d'exemple
├── DOCKER-README.md      # Documentation Docker
├── Dockerfile           # Configuration de l'image Docker
├── docker/              # Dossiers de configuration Docker
│   ├── 000-default.conf
│   └── start.sh
└── [autres fichiers de l'application...]
```

## Prérequis

- Docker installé sur la machine
- Git (pour cloner le dépôt)
- Ports 8080 et 3306 disponibles

## Instructions d'installation

1. **Cloner le dépôt** :
   ```bash
   git clone [URL_DU_DEPOT]
   cd cinephoria-main
   ```

2. **Créer le fichier .env** :
   ```bash
   cp .env.example .env
   ```
   (Ajuster les paramètres si nécessaire dans le fichier .env)

3. **Construire l'image Docker** :
   ```bash
   docker build -t cinephoria .
   ```

4. **Lancer le conteneur** :
   ```bash
   docker run -d -p 8080:80 --name cinephoria-app cinephoria
   ```

## Accès à l'application

- **Application** : http://localhost:8080
- **Base de données** :
  - Hôte : localhost
  - Port : 3306
  - Utilisateur : root
  - Mot de passe : (aucun)
  - Base de données : cinephoria

## Commandes utiles

- **Voir les logs** :
  ```bash
  docker logs cinephoria-app
  ```

- **Accéder au shell du conteneur** :
  ```bash
  docker exec -it cinephoria-app bash
  ```

- **Arrêter l'application** :
  ```bash
  docker stop cinephoria-app
  ```

- **Redémarrer l'application** :
  ```bash
  docker start cinephoria-app
  ```

- **Supprimer le conteneur** :
  ```bash
  docker rm -f cinephoria-app
  ```

## Dépannage

Si l'application ne fonctionne pas :
1. Vérifiez que le conteneur est en cours d'exécution : `docker ps`
2. Consultez les logs : `docker logs cinephoria-app`
3. Vérifiez que les ports 8080 et 3306 ne sont pas utilisés par d'autres applications

## Pour les développeurs

Si vous devez importer une base de données, placez le fichier SQL dans `sql/cinephoria.sql` avant de construire l'image.

Pour reconstruire l'image après des modifications :
```bash
docker build -t cinephoria .
docker rm -f cinephoria-app
docker run -d -p 8080:80 --name cinephoria-app cinephoria
```
