<?php
class Film {
    public static function getAll($pdo) {
        $stmt = $pdo->query("
            SELECT f.*, 
                (SELECT ROUND(AVG(a.note),1) FROM avis a WHERE a.film_id = f.id AND a.valide = 1) AS moyenne
            FROM films f
            ORDER BY f.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($pdo, $titre, $description, $age_minimum, $affiche, $coup_de_coeur, $genres = []) {
        $pdo->beginTransaction();
        try {
            // Ajouter le film
            $stmt = $pdo->prepare("
                INSERT INTO films (titre, description, age_minimum, affiche, coup_de_coeur, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$titre, $description, $age_minimum, $affiche, $coup_de_coeur]);
            $film_id = $pdo->lastInsertId();

            // Ajouter les associations de genres
            if (!empty($genres)) {
                $stmt = $pdo->prepare("INSERT INTO film_genre (film_id, genre_id) VALUES (?, ?)");
                foreach ($genres as $genre_id) {
                    $stmt->execute([$film_id, $genre_id]);
                }
            }

            $pdo->commit();
            return $film_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function modifier($pdo, $id, $titre, $description, $age_minimum, $affiche, $coup_de_coeur, $genres = []) {
        $pdo->beginTransaction();
        try {
            // Mettre à jour le film
            $stmt = $pdo->prepare("
                UPDATE films SET titre = ?, description = ?, age_minimum = ?, affiche = ?, coup_de_coeur = ?
                WHERE id = ?
            
            ");
            $stmt->execute([$titre, $description, $age_minimum, $affiche, $coup_de_coeur, $id]);

            // Mettre à jour les genres
            // D'abord supprimer les anciennes associations
            $stmt = $pdo->prepare("DELETE FROM film_genre WHERE film_id = ?");
            $stmt->execute([$id]);

            // Puis ajouter les nouvelles
            if (!empty($genres)) {
                $stmt = $pdo->prepare("INSERT INTO film_genre (film_id, genre_id) VALUES (?, ?)");
                foreach ($genres as $genre_id) {
                    $stmt->execute([$id, $genre_id]);
                }
            }

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function supprimer($pdo, $id) {
        $pdo->beginTransaction();
        try {
            // Supprimer d'abord les associations de genres
            $stmt = $pdo->prepare("DELETE FROM film_genre WHERE film_id = ?");
            $stmt->execute([$id]);
            
            // Puis supprimer le film
            $stmt = $pdo->prepare("DELETE FROM films WHERE id = ?");
            $stmt->execute([$id]);
            
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }


    
    public static function getGenres($pdo, $film_id) {
        $stmt = $pdo->prepare("
            SELECT g.id, g.nom 
            FROM genres g 
            JOIN film_genre fg ON g.id = fg.genre_id 
            WHERE fg.film_id = ?
        
        ");
        $stmt->execute([$film_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDernierMercredi($pdo) {
        $stmt = $pdo->prepare("
            SELECT * FROM films
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 5) % 7 DAY)
            ORDER BY created_at DESC
        
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFiltered($pdo, $filters = []) {
        // Requête de base pour récupérer les films avec leur note moyenne
        $sql = "
            SELECT f.*, 
                (SELECT ROUND(AVG(a.note),1) FROM avis a WHERE a.film_id = f.id AND a.valide = 1) AS moyenne
            FROM films f
            WHERE 1=1
        ";

        $params = [];

        // Filtre par genre
        if (!empty($filters['genre'])) {
            $sql .= " AND f.id IN (
                SELECT fg.film_id 
                FROM film_genre fg 
                WHERE fg.genre_id = ?
            )";
            $params[] = $filters['genre'];
        }

        // Filtre par cinéma
        if (!empty($filters['cinema_id'])) {
            $sql .= " AND f.id IN (
                SELECT DISTINCT s.film_id 
                FROM seances s 
                JOIN salles sa ON s.salle_id = sa.id
                WHERE sa.cinema_id = ?
            )";
            $params[] = $filters['cinema_id'];
        }

        // Filtre par jour
        if (!empty($filters['jour'])) {
            $sql .= " AND f.id IN (
                SELECT DISTINCT s.film_id 
                FROM seances s 
                WHERE s.date = ?
            )";
            $params[] = $filters['jour'];
        }

        // Si pas de filtres, on affiche tous les films
        if (empty($params)) {
            return self::getAll($pdo);
        }

        $sql .= " ORDER BY f.titre ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public static function getByCinema($pdo, $cinemaId) {
        $stmt = $pdo->prepare("
            SELECT DISTINCT f.*
            FROM films f
            JOIN seances s ON s.film_id = f.id
            JOIN salles sa ON sa.id = s.salle_id
            WHERE sa.cinema_id = ?
        ");
        $stmt->execute([$cinemaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}