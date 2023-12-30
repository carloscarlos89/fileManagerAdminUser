<?php
/**
 * @package filemanager
 */
/*
Plugin Name: filemanager
Plugin URI: 
Description: Administrador de archivos.
Version: 5.0.1
Requires at least: 5.0
Requires PHP: 5.2
Author: carlos4304297
Author URI: 
License: GPLv2 or later
Text Domain: filemanager
*/

/*
 *
 * NUEVO CODIGO
 *
 */
//cargar js
// Declarar un array global
global $cargarcategorias;

// Asignar valores al array
$cargarcategorias = array(
    'cat1' => 'Descuentos',
    'cat2' => 'PagoDeServicios',
    'cat3' => 'CotizacionesDeServicios',
    'cat4' => 'TransferenciasBancarias'
);
//Si no esta el parametro etiqueta entonces se simula
if (!isset($_GET['etiqueta'])) {
    $_GET['etiqueta']='Descuentos';
}

/*
EXAMPLE:
global $cargarcategorias;
$cargarcategorias['cat1'];

*/

/*
 *
 *CAPTURA EL ID ATACHMENT DEL ARCHIVO RECIEN SUBIO Y CORRE LA FUNCION QUE LE AGREGARA UNA ETIQUETA
 *
 */
// Función que se ejecutará después de subir un archivo adjunto
function ejecutar_despues_de_subir_pdf($attachment_id)
{
    /*
     *
     *ANEXA UNA ETIQUETA A UN POST ATACHMENT
     *
     */

    // Obtener el post attachment
    $attachment_post = get_post($attachment_id);
    // Verificar si el post attachment existe y es del tipo "attachment"
    if ($attachment_post && $attachment_post->post_type === 'attachment') {
        global $cargarcategorias;

        // Agregar la nueva etiqueta si aún no está presente
        if ($_GET['etiqueta'] == $cargarcategorias['cat1']) {
            wp_set_post_tags($attachment_id, $cargarcategorias['cat1'], true);
        } else if ($_GET['etiqueta'] == $cargarcategorias['cat2']) {
            wp_set_post_tags($attachment_id, $cargarcategorias['cat2'], true);
        } else if ($_GET['etiqueta'] == $cargarcategorias['cat3']) {
            wp_set_post_tags($attachment_id, $cargarcategorias['cat3'], true);
        } else if ($_GET['etiqueta'] == $cargarcategorias['cat4']) {
            wp_set_post_tags($attachment_id, $cargarcategorias['cat4'], true);
        }



    } else {
        error_log('No se encontró ningún post attachment con el ID ' . $attachment_id);
    }




}
add_action('add_attachment', 'ejecutar_despues_de_subir_pdf');
/*
 *
 *MUESTRA TODOS LOS POST ATACHMENT QUE TIENEN UNA ETIQUETA ESPECIFICA
 *
 */
function ImprimeTodosLosArchivosPdfEtiquetaEspecifica()
{
    if (isset($_GET['etiqueta'])) {
        // Obtener el ID del término según el slug
        $term = get_term_by('slug', $_GET['etiqueta'], 'post_tag'); // Cambia 'post_tag' por la taxonomía deseada si no es un tag

        if ($term) {
            // Obtener publicaciones adjuntas relacionadas con el término
            $args = array(
                'post_type' => 'attachment', // Tipo de post (attachment en este caso)
                'post_status' => 'inherit', // Estado de los posts
                'tax_query' => array(
                    array(
                        'taxonomy' => 'post_tag', // Taxonomía (post_tag para tags)
                        'field' => 'slug',
                        'terms' => $term->slug, // Slug del término
                    ),
                ),
                'posts_per_page' => -1, // Mostrar todas las publicaciones
            );

            $query = new WP_Query($args);

            // Mostrar las publicaciones encontradas
            $datos = array(); // Inicializar el array vacío

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();

                    // Obtener la fecha del post
                    $post_date = get_the_date('Y-m-d', $post_id);

                    // Obtener la URL de descarga
                    $attachment_url = wp_get_attachment_url($post_id);

                    // Obtener la ruta del archivo adjunto (_wp_attached_file)
                    $meta_value = get_post_meta($post_id, '_wp_attached_file', true);
                    // Obtener el ID del attachment a partir de la ruta del archivo
                    $attachment_id = attachment_url_to_postid($meta_value);
                    // Genera la url a eliminar archivo
                    $urlEliminarArchivo = eliminarArchivo($post_id);
                    $nombre_archivo = basename($meta_value);

                    // Almacenar los datos en un array
                    $datos[] = array(
                        'dato1' => $nombre_archivo,
                        'dato2' => $post_date,
                        'dato3' => $attachment_url,
                        'dato4' => $urlEliminarArchivo
                    );
                }
                wp_reset_postdata(); // Restaurar datos de la publicación
                return $datos;
            } else {
                echo 'No se encontraron attachments relacionados con el término.';
            }
        }

    } else {
        error_log("no es igual");
    }
}
function eliminarArchivo($valor_a_eliminar)
{
    global $wp;
    // Obtener la URL actual
    $url_actual = home_url( $_SERVER['REQUEST_URI'] );
    // Verificar si la variable contiene "/intranet/intranet/"
if (strpos($url_actual, "/intranet/intranet/") !== false) {
    // Reemplazar "/intranet/intranet/" con "/intranet/"
    $url_actual = str_replace("/intranet/intranet/", "/intranet/", $url_actual);
}
    // Parámetro a añadir
//$valor_a_eliminar = 'xx';

    // Anexar el parámetro "eliminar" con el valor específico a la URL actual
    $url_con_parametro = add_query_arg('eliminar', $valor_a_eliminar, $url_actual);

    // Imprimir la URL con el parámetro agregado
    return $url_con_parametro;

}
function validadorEliminarArchivo()
{
    if (isset($_GET['etiqueta']) || isset($_GET['eliminar'])) {
        if (isset($_GET['eliminar'])) {
            // Eliminar el post por su ID
            wp_delete_post($_GET['eliminar'], true); // El segundo parámetro true vacía la papelera

        }

    }

}
add_action('init','validadorEliminarArchivo');
/*
 *
 *CREA SHORTCODE PARA PODER VISUALIZAR LOS PDF ETIQUETADOS
 *
 */
/*
 *
 *CREA BOTON SUBIR ARCHIVO
 *
 */
// Agregar un action hook para manejar la subida del archivo PDF
function myFileUploader()
{
    if (isset($_POST['submit'])) {
        $resultado_subida = wp_upload_bits($_FILES['fileToUpload']['name'], null, file_get_contents($_FILES['fileToUpload']['tmp_name']));
        if ($resultado_subida['error']) {
            echo 'Error al subir el archivo: ' . $resultado_subida['error'];
        } else {
            // Crear un nuevo adjunto en la base de datos de WordPress
            $attachment = array(
                'post_mime_type' => 'application/pdf', // Tipo MIME del archivo (ajusta según el tipo de archivo)
                'post_title' => 'Título del archivo', // Título del adjunto
                'post_content' => '', // Contenido del adjunto (opcional)
                'post_status' => 'inherit'
            );

            // Insertar el adjunto en la base de datos y obtener su ID
            $attachment_id = wp_insert_attachment($attachment, $resultado_subida['file']);

            if (!is_wp_error($attachment_id)) {
                error_log('Archivo subido con éxito. ID del adjunto: ' . $attachment_id);
            } else {
                error_log('Error al crear el adjunto: ' . $attachment_id->get_error_message());
            }
        }
    }
    //if ( current_user_can( 'manage_options' ) ) {
    // Tu código para el usuario administrador aquí
    // Esta parte se ejecutará solo si el usuario actual tiene el rol de administrador
    echo '
      <form action="" method="post" enctype="multipart/form-data">
        <input type="file" class="input-file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Subir archivo" name="submit">
      </form>
    ';
    //}

}
function VerificarEstadoLink($categoriaActiva)
{
    if (isset($_GET['etiqueta'])) {
        if ($_GET['etiqueta'] == $categoriaActiva) {
            return 'activo';
        } else {
            return;
        }
    }
    
}

function visual()
{
    $plugin_dir = plugin_dir_path(__FILE__);
    include($plugin_dir . 'src/estilos.php');
    estilos();
    global $cargarcategorias;
    $margin = '';
    if (is_admin()) {
        $margin = 'margin-top:2em;';
    }
    echo "<div class='sectionfilemanager' style='" . $margin . "'>";
    echo "<div class='menu_mobile'>";
    echo "<div class='menu_mobile_section'>";
    echo "<a class='categoria " . VerificarEstadoLink($cargarcategorias['cat1']) . "' href='" . ObtenerUrlFiltrarCategoria($cargarcategorias['cat1']) . "'>Descuentos</a> ";
    echo "<a class='categoria " . VerificarEstadoLink($cargarcategorias['cat2']) . "' href='" . ObtenerUrlFiltrarCategoria($cargarcategorias['cat2']) . "'>Pagos de servicios</a> ";
    echo "<a class='categoria " . VerificarEstadoLink($cargarcategorias['cat3']) . "' href='" . ObtenerUrlFiltrarCategoria($cargarcategorias['cat3']) . "'>Cotizaciones de servicios</a> ";
    echo "<a class='categoria " . VerificarEstadoLink($cargarcategorias['cat4']) . "' href='" . ObtenerUrlFiltrarCategoria($cargarcategorias['cat4']) . "'>Transferencias bancarias</a> ";
    echo "</div>";
    echo "</div>";
    //subir archivo section
    ob_start();
    if (is_admin()) {
        myFileUploader();
    }
    //Aviso error
    if (!isset($_GET['etiqueta'])) {
        //si no se elecciono una categoria entonces redirecciona
        
    }
    //Incluir el archivo HTML desde el directorio del plugin
    include($plugin_dir . 'example/index.php');
    encabezadoTabla();
    // Ejemplo de datos para el array $parametros
    $datosarray = ImprimeTodosLosArchivosPdfEtiquetaEspecifica();
    if (isset($_GET['etiqueta'])) {
    // Llamar a la función para generar las filas de la tabla
    filaTabla($datosarray);
    }
    footerTabla();
    echo "</div>";
    $contenido = ob_get_clean();
    echo $contenido;
}
add_shortcode('visual0', 'visual');
add_shortcode('imprimirAdministradorArchivos', 'visual');

// Agregar el menú en el panel de administración
add_action('admin_menu', 'agregar_menu_admin');

function agregar_menu_admin()
{
    // Añadir un nuevo elemento de menú en el panel de administración
    add_menu_page(
        'Administrador de archivos',
        'Administrador de archivos',
        'manage_options',
        'identificador-menu',
        'visual',
        'dashicons-admin-generic', // Ícono opcional: aquí se utiliza un icono predeterminado de WordPress
        6 // Prioridad del menú (puedes ajustarlo según tus necesidades)
    );
}



function ObtenerUrlFiltrarCategoria($filtro)
{
    global $post;
    $pagina_url = get_permalink($post);
    // Obtener la URL actual
    $actual_url = $pagina_url;

    // Agregar el parámetro "etiqueta" con el valor "mario"
    $actual_url_con_etiqueta = add_query_arg('etiqueta', $filtro, $actual_url);

    return esc_url($actual_url_con_etiqueta);

}
