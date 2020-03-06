<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'wordpress' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '123' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'wordpress_db' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ts!@NwQP9pQR!@%6$!3|m~[y{?&5EPX#wA0=TtR#L7ZexC#$J8A&$$/3ZBG#E%eg' );
define( 'SECURE_AUTH_KEY',  'XCURjvv(_jQ2d<M<lbeLxIJ.fA;9A@pfcxU`^M`m@HI@u z~*F2/.PL%xpeb<LZ]' );
define( 'LOGGED_IN_KEY',    '$w|2>AAakKSf0|!6d2|J?i+Z..POD`3LE8W{*RI!4:Pu-j9KOB2O3AZ40LV)tV:U' );
define( 'NONCE_KEY',        'rZpZB|kldmiGJoZx8]Hova(2zM_CY3zo%=f%rqFJ3Gt:1ouMyr}f^u(l]h{f<K2b' );
define( 'AUTH_SALT',        'b8dk&|B8#Bsg~G^+LHIK|, Gy2eKF,ZTQJu=0b[*<PU,SG)UoAg`|MJv&H>21W}s' );
define( 'SECURE_AUTH_SALT', '/HD)2GnGD0vbIfVC:XOkK>DeF;xGU.^%2O&p.<8jDlpjp6<%fni<MEN1?:71~x;v' );
define( 'LOGGED_IN_SALT',   '&ocjVeS9aQl0cP2@.~|P.o@!/nS?b#Uv[|50{xO,3PHf}>{]55bKWCL`N^6 ?+fx' );
define( 'NONCE_SALT',       ',Ct`)q5 7~o,4i!>ye7<iUI[,jvW6r]}7hFNE@NFE[s0q/_s21/%!yiA=48U$%4_' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
