# Controle DO


## Test De Qualite

On verifie la fonctionnalitem maintenabilite, securite et performance du code fournie. 

1. Analyse statique (lecture sans execution)
- Linting :\
    app/index.php:\
        - Invalid HTML Nesting: le tag h2 est ferme avant que le tag small est ferme.\
        - Le tag cite n'est pas ferme correctement  ( cite a la place de /cite)

    app/validation.php\
        - !isset($_POST["title"]) doit etre !isset($_POST["authour"]) en 2eme elseif

- Security :
    - L'application lis le contenu comme tel enregistre ce qui peut entraine a l'execution d'un script malicieux\

- Test de qualite:\
    - script q_test.php genere par Gemini comme matiere premiere puis revise par l'auteur qui elabore un test dynamique pour verifier la connection au base de donnees, la cohesion des statements au validation.php, et le nesting et tags HTML.
    ```bash
        #!/bin/bash
        # Executer le script de test php dans le container app
        docker-compose exec app php q_test.php
    ```
    

