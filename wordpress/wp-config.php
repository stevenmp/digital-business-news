<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ',hcs6iLx]`5pFhp-oW>Y>{Q_=,<-?Tn,uU=49OmF,T^[eTJ+s#Ky~ /aC >VWN2`');
define('SECURE_AUTH_KEY',  'sFG_s,J`!^~B0qR$Z{3`+nMY@JjFo(S..f#*(w.BVFdWLyUF_X7y+`_XV.y6yRG|');
define('LOGGED_IN_KEY',    '8xmj<@8n/Y~v;5PWAl%}C>@4zC#v:H3)X6$]3b})e1BMn d,<U&pO<1,m~y1Ijyl');
define('NONCE_KEY',        'vWHmb`:NWO>Q&US%-sI[KNrgTF%2bk]^%]91>M6m=.dZm5)u/2l%KC0/XU K1x<3');
define('AUTH_SALT',        'bo?Y% u2~w,#_WCtia S6H34.v+N 1,>?P(2k,BB?c.nD8q*[zP%=FMrn)W%`E.6');
define('SECURE_AUTH_SALT', 'F}.Ua_4sk4yaufZ@1ovQ*O-[1$YGVG,aQ1mfMP.:u!vc,{*;+{R3#iFKMZZTw1U,');
define('LOGGED_IN_SALT',   'Qtyn.#Y[HdE^b71HkwJjOBr)25+xfSs3X:_K5bj`Y:}788+<eY!q_Y%KIe.jqULw');
define('NONCE_SALT',       '$G8x$Bf![BwT+#m~yTxc-LFS{tKMJMFo4CHKW7) `ieNq*L;0X,AknKX{,}#PVDy');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');