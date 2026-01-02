# Controle DO


## Test De Qualite

On verifie la fonctionnalitem maintenabilite, securite et performance du code fournie. 

### 1. Analyse statique 
- Linting :\
    app/index.php:\
        - Invalid HTML Nesting: le tag h2 est ferme avant que le tag small est ferme.\
        - Le tag cite n'est pas ferme correctement  ( cite a la place de /cite)

    app/validation.php\
        - !isset($_POST["title"]) doit etre !isset($_POST["authour"]) en 2eme elseif

- Security :
    - test SAST (Static Application Security Testing) va scanner le code contre les SQL injections pour voir si les requetes sont prepares ou concatene.
    - le code est vulnerable aux attaques Cross-Site Scripting (XSS), affichage des donnes de la base de donnees sans desinfection ce qui peut entraine a l'execution d'un script malicieux

- Detection du code mort :
    - Il semble que toutes les variables mentiones sont utilises


- Test de qualite :
    ```bash
        #!/bin/bash
        # Executer le script de test php dans le container app
        docker-compose exec app php q_test.php
    ```
    

