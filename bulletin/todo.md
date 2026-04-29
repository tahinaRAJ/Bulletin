- [ ] Base
    - [ ] initialisation de la base de données mySQl
    - [ ] création de la table user
        - id
        - nom
        - mdp
  
    - [ ] création de la table etudiant
        - id(ex: 003469)
        - nom

    - [ ] Creation de la table matiere
        - id
        - numero
        - nom 
        - coef
        - id_semstre
        - optional(1 or 0)

    - [ ] Creation de la table semestre
        - id

    - [ ] Creation de la table option
        - id 
        - label
        
    - [ ] Creation de la table note 
        - id
        - id_etu
        - id_matiere
        - note

    - [ ] Creation de la vue v_note_par_option (group by option , si pas d'option c'est S3 , on prend la matiere optionel avec la note la plus elevees parmi les notes optionnelles )
        - id_etu
        - note
        - id_matiere
        - coef
        - id_option
  
    - [ ] Creation de la vue v_moyenne_par_option (group by option , si pas d'option c'est S3, on prend les notes par ooptions et on en fait une moyenne )
        - id_etu
        - moyenne
        - coef_total
        - id_option
  
  
    - [ ] Creation de la table matiere_semestre
        - id_semestre
        - id_matiere 
        - option (nullable sauf en S4)
  
    - [ ] Creation de la vue v_moyenne_annee_par_option (L2 )
        - id_etudiant  
        - note
        - id_matiere

- [ ] Création de la page

    - [ ] Adaptation du template login
      - [ ] Mettre des valeurs par défaut
      - [ ]  Creation de routes : "/login"
      - [ ]  Creation de controller : "AuthController"
        - [ ]  Creation de la fonction verifier(user,mdp) :: regarder dans la base puis rediriger vers la page list des etudiants
      
    - [ ] Creation de la page liste etudiant 
      - [ ] Adapter le template 
      - [ ] Creation du Controller "EtuController"
        - [ ] Creation de la fonction findAllEtudiants() :: appeler le model "EtuModel"

      - [ ] Creation du model "EtuModel"
        - [ ] Creation de la fonction findAllEtudiants():: Acceder a la base \
    
    - [ ] Creation de la page "insertionNotes"
      - [ ]  Adaptation du template
      - [ ]  Liste deroulante pour l'etu
      - [ ]  liste deroulante pour la matiere
      - [ ] Creation du controller "NoteController"
        - [ ] Creation de la fonction insererNote(id_etu,note,id_matiere)
      - [ ] Creation du model "NoteModel"
        - [ ] Creation de la fonction insererNote(id_etu,note,id_matiere) : verifier que la note n'est pas negative et entre 0 a 20 , si on  insere plusieurs fois pour la meme note et meme matiere  , a chaque nouvelle insertion , on prend le plus grand entre l'inertion et la base  

    -[ ] Creation de la page ficheEtu
      - [ ] Modification du controller "NoteController"
        - [ ] Creation de la fonction findAllNotesByoption(id_etu)
        - [ ] Creation de la fonction findAllMoyenneByYear(id_etu)
        - [ ] Creation de la fonction findAllMoyenneBySemestre(id_etu)
      - [ ] Modification du model "NoteModel"
        - [ ] creation de la fonction findAllNotesByOption(id_etu)