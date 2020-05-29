# Plugins CRUD Car

Ce plugin permet côté administrateur de : 

  * Créer un véhicule 
  * Supprimer un véhicule
  * Modifier un véhicule
  * Newsletter avec modification des champs objet, expéditeur et message
  
Côté client, vous pouvez ajouter 2 widgets grâce à ce plugin. En effet, le plugin Newsletter permet d'afficher un formulaire avec un simple input email

Pour ce qui est des véhicules, ça sera un tableau reprenant tous les véhicules disponible.
  

## Installation

Pour installer ce projet, il vous suffit de reprendre le dossier en faisant un git clone ou git init du projet :

```bash
git clone https://github.com/Djaizansi/plugin_wordpress.git
```

Ou de récupèrer le dossier directement en téléchargeant le .zip

Ensuite prenez ce dossier et coller le dans votre dossier "Plugin" dans votre projet wordpress

Une fois tout est prêt, lancer Wordpress et aller dans vos extensions. Vous verrez le plugin.

Il suffira tout simplement de l'activer et c'est parti !

## Configuration Newsletter

Si vous voulez utiliser le système de Newsletter, il vous faudra configurer les paramètres d'envoie.
En effet, pour configurer ces derniers, aller dans vos fichier wordpress et aller dans vos thèmes. Il vous faudra ajouter un bout de code à la fin du fichier "functions.php" : 

```php
add_action( 'phpmailer_init', 'my_phpmailer_configuration' );
function my_phpmailer_configuration( $phpmailer ) {
    $phpmailer->isSMTP();     
    $phpmailer->Host = 'smtp.exemple.com';
    $phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
    $phpmailer->Port = Port; //SSL : 465 / TLS : 587
    $phpmailer->Username = 'utilisateur';
    $phpmailer->Password = 'motdepasse';

    // Configurations complémentaires
    $phpmailer->SMTPSecure = "ssl"; // Sécurisation du serveur SMTP : ssl ou tls
    $phpmailer->From = "wordpress@exemple.com"; // Adresse email d'envoi des mails
    $phpmailer->FromName = "Nom Exemple"; // Nom affiché lors de l'envoi du mail
}
```

## Contributor

* **JALLALI Youcef** _alias_ [@Djaizansi](https://github.com/Djaizansi)
* **BOUADDELLAH Marwane** _alias_ [@BOUABDELLAHM](https://github.com/BOUABDELLAHM)
* **DISPAGNE Mel** _alias_ [@lumay](https://github.com/lumay)
* **WELLE Guillaume** _alias_ [@gwelle](https://github.com/gwelle)
