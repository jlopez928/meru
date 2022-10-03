<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Helper
{
    /**
     * Retorna el código JS para un Confirm de Sweet Alert
     *
     * @param  string  $selector
     * @param  string  $msg
     * @param  string  $formSelector
     *
     * @return string
     */
    public static function swalConfirm($selector, $msg=null, $formSelector='')
    {
        $msg = $msg ?: 'Este registro será eliminado. Esta acción no puede ser reversada.';

        return <<<HTML
            <script>
                $(document).on('click', '$selector', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Está seguro?',
                        text: '$msg',
                        icon: 'warning',
                        confirmButtonText: '¡Sí!',
                        cancelButtonText: 'Cancelar',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    })
                    .then ((result) => {
                        if (result.value) {
                            form = '$formSelector'
                                ? $('$formSelector')
                                : $('#'+$(this).attr('form-target'));

                            if (this.name) {
                                form.append(`<input name='\${this.name}' value='\${this.value}'>`)
                            }

                            form.submit();
                        }
                    })
                });
            </script>
HTML;
    }

    public static function formatNumber($num, $dec, $puntodecimal, $sepmiles, $neg = null)
    {
        $f = number_format($num, $dec, $puntodecimal, $sepmiles);

        if (strstr($f,'-') && !is_null($neg)) {
            $f = str_replace('-', '(', $f);
            $f .= ')'; 
        }

        return $f;
    }
}