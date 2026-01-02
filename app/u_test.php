<?php
/**
 * Script de Tests Unitaires (u_test.php)
 * 
 * Ce script teste les fonctions unitaires de l'application.
 * Il se concentre principalement sur la fonction getArticles() dans index.php.
 */

// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fonction d'assertion personnalisée pour afficher les résultats
function assertTest($condition, $nomTest) {
    if ($condition) {
        echo "\033[32m[PASS]\033[0m $nomTest\n";
    } else {
        echo "\033[31m[FAIL]\033[0m $nomTest\n";
        exit(1); // Quitter avec erreur si un test échoue
    }
}

echo "Démarrage des tests unitaires...\n";
echo "================================\n";

// 1. Chargement de l'application
// Nous utilisons la mise en mémoire tampon (output buffering) pour inclure index.php
// sans afficher le HTML qu'il génère, afin d'accéder à la fonction getArticles().
ob_start();
// On vérifie si le fichier existe avant
if (file_exists('index.php')) {
    require 'index.php';
} else {
    echo "\033[31m[ERREUR]\033[0m Impossible de trouver index.php\n";
    exit(1);
}
ob_end_clean();

echo "Environnement chargé.\n";

// 2. Initialisation de la connexion pour les tests
// Les constantes DB_DSN, DB_USER, DB_PASS sont définies dans db-config.php (inclus par index.php)
try {
    global $options; // $options est défini dans db-config.php
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, isset($options) ? $options : []);
    assertTest(true, "Connexion à la base de données établie");
} catch (PDOException $e) {
    echo "\033[31m[ERREUR]\033[0m Connexion échouée: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Test de la fonction getArticles()

// Test 3.1 : Vérifier que la fonction retourne un tableau
$articles = getArticles($pdo);
assertTest(is_array($articles), "getArticles() retourne un tableau");

// Test 3.2 : Insertion d'un article de test et vérification de sa récupération
$testTitle = "Unit Test Title " . uniqid();
$testAuthor = "Unit Tester";
$testContent = "Ceci est un contenu de test unitaire.";

// Insertion manuelle
$stmt = $pdo->prepare("INSERT INTO articles (title, author, content, date) VALUES (:title, :author, :content, NOW())");
$stmt->bindValue(':title', $testTitle);
$stmt->bindValue(':author', $testAuthor);
$stmt->bindValue(':content', $testContent);
$success = $stmt->execute();

assertTest($success, "Insertion d'un article de test en base de données");

// Récupération via la fonction à tester
$articles = getArticles($pdo);
$foundArticle = null;

foreach ($articles as $art) {
    if ($art['title'] === $testTitle) {
        $foundArticle = $art;
        break;
    }
}

assertTest($foundArticle !== null, "L'article inséré est retrouvé par getArticles()");

// Test 3.3 : Vérification de l'intégrité des données
if ($foundArticle) {
    assertTest($foundArticle['author'] === $testAuthor, "L'auteur correspond");
    assertTest($foundArticle['content'] === $testContent, "Le contenu correspond");
    assertTest(isset($foundArticle['date']), "La date est présente");
    
    // 4. Nettoyage (Teardown)
    // Suppression de l'article de test pour ne pas polluer la base
    $delStmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
    $delStmt->bindValue(':id', $foundArticle['id']);
    $delStmt->execute();
    echo "Nettoyage des données de test effectué.\n";
}

echo "================================\n";
echo "Tous les tests unitaires sont passés.\n";