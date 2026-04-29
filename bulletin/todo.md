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

    - [ ] Creation de la vue v_note_par_option (group by option)
        - id_etu
        - note
        - id_matiere
        - coef
        - id_option
    
    - [ ] Creation de la table semestre
        - id
  
    - [ ] Creation de la table matiere_semestre
        - id_semestre
        - id_matiere 
        - option (nullable sauf en S4)

- [ ] Création de la page
    - [ ] Adaptation du template login
    - [ ] Mettre des valeurs par défaut 

