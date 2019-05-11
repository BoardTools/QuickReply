<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2019 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'QR_ATTACH_NOTICE'                 => 'Cette réponse contient au moins un fichier joint.',
	'QR_BBCODE'                        => 'BBCode',
	'QR_CANCEL_SUBMISSION'             => 'Annuler l’envoi',
	'QR_CTRL_ENTER'                    => 'Il est aussi possible d’envoyer la réponse au moyen de la combinaison des touches du clavier « Ctrl » + « Entrée ».',
	'QR_FORM_HIDE'                     => 'Masquer le formulaire de la réponse rapide',
	'QR_FULLSCREEN'                    => 'Éditeur de texte complet',
	'QR_FULLSCREEN_EXIT'               => 'Sortir du mode plein écran',
	'QR_INSERT_TEXT'                   => 'Insérer une citation dans le formulaire de la réponse rapide',
	'QR_QUICKQUOTE'                    => 'Citation rapide',
	'QR_QUICKQUOTE_TITLE'              => 'Répondre avec la citation rapide',
	'QR_LOADING'                       => 'Chargement en cours…',
	'QR_LOADING_ATTACHMENTS'           => 'Chargement en cours des fichiers joints sur le serveur…',
	'QR_LOADING_NEW_FORM_TOKEN'        => 'Le session du formulaire était expirée et a été mise à jour.<br />Merci d’envoyer le formulaire à nouveau.',
	'QR_LOADING_NEW_POSTS'             => 'Au moins une nouvelle réponse a été publiée dans le sujet.<br />Votre réponse n’a pas été envoyée pour vous permettre de modifier celle-ci.<br />Récupération des nouvelles réponses en cours…',
	'QR_LOADING_PREVIEW'               => 'Affichage du l’aperçu en cours…',
	'QR_LOADING_SUBMITTED'             => 'Votre message a été envoyé avec succès.<br />Affichage du résultat en cours…',
	'QR_LOADING_SUBMITTING'            => 'Envoi en cours de la réponse…',
	'QR_LOADING_WAIT'                  => 'En attente de la réponse du serveur…',
	'QR_MORE'                          => 'Actions supplémentaires',
	'QR_NO_FULL_QUOTE'                 => 'Merci de sélectionner une partie du message afin de citer celui-ci.',
	'QR_PREVIEW_CLOSE'                 => 'Fermer l’aperçu',
	'QR_PROFILE'                       => 'Voir le profil',
	'QR_QUICKNICK'                     => 'Se référer au nom d’utilisateur',
	'QR_QUICKNICK_TITLE'               => 'Insérer le nom d’utilisateur dans le formulaire de la réponse rapide',
	'QR_REPLY_IN_PM'                   => 'Répondre dans un MP',
	'QR_TYPE_REPLY'                    => 'Merci de saisir le texte de votre réponse ici…',
	'QR_WARN_BEFORE_UNLOAD'            => 'Le texte de votre réponse saisi n’a pas été envoyé et semble avoir été perdu !',
	// begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Translittérer',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'en russe', // can be changed to your language here and below
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'Pour modifier le texte en russe cliquer sur le bouton',
	'QR_TRANSLIT_FROM'                 => 'je,jo,ayu,ay,aj,oju,oje,oja,oj,uj,yi,ya,ja,ju,yu,ja,y,zh,i\',shch,sch,ch,sh,ea,a,b,v,w,g,d,e,z,i,k,l,m,n,o,p,r,s,t,u,f,x,c,\'e,\',`,j,h', // language specific adaptation required (do not use spaces or line breaks), use commas as separators here and below
	'QR_TRANSLIT_TO'                   => 'э,ё,aю,ай,ай,ою,ое,оя,ой,уй,ый,я,я,ю,ю,я,ы,ж,й,щ,щ,ч,ш,э,а,б,в,в,г,д,е,з,и,к,л,м,н,о,п,р,с,т,у,ф,х,ц,э,ь,ъ,й,х',
	'QR_TRANSLIT_FROM_CAPS'            => 'Yo,Jo,Ey,Je,Ay,Oy,Oj,Uy,Uj,Ya,Ja,Ju,Yu,Ja,Y,Zh,I\',Sch,Ch,Sh,Ea,Tz,A,B,V,W,G,D,E,Z,I,K,L,M,N,O,P,R,S,T,U,F,X,C,EA,J,H',
	'QR_TRANSLIT_TO_CAPS'              => 'Ё,Ё,Ей,Э,Ай,Ой,Ой,Уй,Уй,Я,Я,Ю,Ю,Я,Ы,Ж,Й,Щ,Ч,Ш,Э,Ц,А,Б,В,В,Г,Д,Е,З,И,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Э,Й,Х',
	// end mod Translit
	// begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Modifier la casse du texte',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Appuyer sur un bouton pour modifier la casse du texte sélectionné',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'minuscules',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'MAJUSCULES',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'iNVERSER lA cASSE',
	// end mod CapsLock Transform
));
