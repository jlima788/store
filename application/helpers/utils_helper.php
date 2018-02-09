<?php
if (!function_exists('load_content')) {
    function load_content($name_content, $data = array()) {
        $CI = & get_instance();
        
        if (empty($data["content"])) $data["content"] = array();
        if (empty($data["footer_content"])) $data["footer_content"] = array("javascripts" => array(), "logged" => $CI->session->userdata('usuario') ? true : false);
        if (empty($data["header_content"])) $data["header_content"] = array("title" => DEFAULT_TITLE, "stylesheets" => array());
        if (empty($data["content"])) $data["content"] = array("msg" => "", "data" => "");
        
        $CI->load->view('header', array(
                "title" => empty($data["header_content"]["title"]) ? "Newmfx" : $data["header_content"]["title"], 
                "stylesheets" => !empty($data["header_content"]["stylesheets"]) ? $data["header_content"]["stylesheets"] : array(), 
                "nome"=>isset($CI->session->userdata('usuario')->tx_nome) ? $CI->session->userdata('usuario')->tx_nome : "",
                "email" => isset($CI->session->userdata('usuario')->usuemail) ? $CI->session->userdata('usuario')->usuemail : "",
                "nome" => isset($CI->session->userdata('usuario')->usunome) ? $CI->session->userdata('usuario')->usunome : "",
                "id" => isset($CI->session->userdata('usuario')->usucod) ? $CI->session->userdata('usuario')->usucod : "",
                "logged" => $CI->session->userdata('usuario') ? true : false,
                "msg" => $CI->session->flashdata('msg') ? $CI->session->flashdata('msg') : array()
            )
        );
        $CI->load->view($name_content, array(
                "data" => !empty($data["content"]["data"]) ? $data["content"]["data"] : array(), 
                "data_options" => isset($data["content"]["data_options"]) ? $data["content"]["data_options"] : array(), 
                "path" => get_class_url(), 
                "msg" => $CI->session->flashdata('msg') ? $CI->session->flashdata('msg') : "", 
                "pagination" => !empty($data["content"]["links"]) ? $data["content"]["links"] : "", 
                "page" => !empty($data["content"]["page"]) ? "/" . $data["content"]["page"] : "", 
                "email" => isset($CI->session->userdata('usuario')->usuemail) ? $CI->session->userdata('usuario')->usuemail : "", 
                "nome" => isset($CI->session->userdata('usuario')->usunome) ? $CI->session->userdata('usuario')->usunome : "", 
                "id" => isset($CI->session->userdata('usuario')->usucod) ? $CI->session->userdata('usuario')->usucod : "", 
                "logged" => $CI->session->userdata('usuario') ? true : false
            )
        );
        $CI->load->view('footer', $data["footer_content"]);
    }
}
if (!function_exists('get_class_url')) {
    function get_class_url() {
        $CI = & get_instance();
        $url = "";
        $urlSegment = $CI->uri->segment(1);
        $routerClass = $CI->router->fetch_class();
        if ($urlSegment != $routerClass) {
            if (!empty($urlSegment)) $url.= $urlSegment . "/";
            if (!empty($routerClass)) $url.= $routerClass;
        } 
        else {
            $url.= $routerClass;
        }
        return $url;
    }
}
if (!function_exists("money_format_english")) {
    function money_format_english($number, $qtd = 2, $decimal = ".", $centena = "") {
        return number_format($number, $qtd, $decimal, $centena);
    }
}
if (!function_exists('get_ip_address_client')) {
    function get_ip_address_client() {
        $ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else $ip = $_SERVER['REMOTE_ADDR'];
        return !empty($ip) ? $ip : array();
    }
}

if (!function_exists('diff_date')) {
    function diff_date($ini = null, $end = null, $str_dias = true, $formata_string_data = true) {
        
        if (is_null($end)) {
            $end = date('Y-m-d');
        }
        
        if (is_null($ini)) {
            $ini = date('Y-m-d');
        }
        
        $startDate = new DateTime($ini);
        $endDate = new DateTime($end);
        $diff = $startDate->diff($endDate);
        
        if ($str_dias) return string_date($diff, $formata_string_data);
        
        return $diff;
    }
}
if (!function_exists("gera_id_solicitacao")) {
    function gera_id_solicitacao() {
        $CI = & get_instance();
        $CI->load->model("Solicitacao_model","Solicitacao",true);
        $solicitacao = $CI->Solicitacao->get(null, null, true);
        if ($solicitacao) {
            $solicitacao            = array_shift($solicitacao);
            $explode_id_solicitacao = explode("/", $solicitacao->id_solicitacao);
            if (date("y") == $explode_id_solicitacao[1]) {
                $numero_ct = str_replace("CT", "", $explode_id_solicitacao[0]);
                $numero_ct += 1;
                $numero_ct = str_pad($numero_ct, 3, "0", STR_PAD_LEFT);
                $id_solicitacao = "CT" . $numero_ct . "/" . date("y");
            } else {
                $id_solicitacao = "CT001/" . date("y");
            }
        } else {
            $id_solicitacao = "CT001/" . date("y");
        }
        return $id_solicitacao;
    }
}
if (!function_exists("dias_data")) {
    function dias_data($date = null, $num = "") {
        return date("Y-m-d", strtotime("$num ".$date));
    }
}

if (!function_exists('string_date')) {
    function string_date($obj, $diferenca = true) {
        
        if (!is_object($obj)) return 0;
        
        $dias = $obj->days;
        
        if ($diferenca) {
            $dias = ($obj->invert == 1 ? "-" . $dias : $dias);
        } 
        else {
            $diferenca_1 = $obj->invert == 1 ? "Ontem" : "Amanhã";
            
            if ($dias == 1) {
                $dias = $diferenca_1;
            } 
            else {
                if ($dias == 0) {
                    $dias = "Hoje";
                } 
                else {
                    $dias = ($obj->invert == 1 ? "-" . $dias : $dias);
                }
            }
        }
        
        return $dias;
    }
}

if (!function_exists("convert_object_in_array")) {
    function convert_object_in_array($object) {
        $array = array();
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }
}

if (!function_exists("calcular_data")) {
    function calcular_data($ini = "", $fim) {
        $data_ini = $ini != "" ? new DateTime($ini) : new DateTime();
        $data_fim = new DateTime($fim);
        $diff = $data_ini->diff($data_fim);
        return $diff->format('%a days and %h hours and %i minute ago');
    }
}

if (!function_exists("reply_json_ajax")) {
    function reply_json_ajax($data, $option_reply = array(), $validation = true) {
        if ($validation) {
            if ($data) {
                $option_reply["status"] = true;
                $option_reply["data"] = $data;
                echo json_encode($option_reply);
            } 
            else {
                $option_reply["status"] = false;
                echo json_encode($option_reply);
            }
        } 
        else {
            echo json_encode($data);
        }
    }
}

if (!function_exists("convert_date")) {
    function convert_date($data, $hours = false, $isOldDate = false) {
        if (!$isOldDate) {
            $timestamp = strtotime($data);
            if (!$hours) $data = date("d/m/Y", $timestamp);
            else $data = date("d/m/Y H:i:s", $timestamp);
            return $timestamp > 0 ? $data : "";
        } 
        else {
            if (!empty($data)) {
                $data = explode("-", $data);
                return $data = $data[2] . "/" . $data[1] . "/" . $data[0];
            } 
            else {
                return "";
            }
        }
    }
}

if (!function_exists("convert_date_format")) {
    function convert_date_format($data, $format) {
        $timestamp = strtotime($data);
        $data = date($format, $timestamp);
        return $timestamp > 0 ? $data : "";
    }
}

if (!function_exists("convert_hours")) {
    function convert_hours($data) {
        $timestamp = strtotime($data);
        $data = date("H:i", $timestamp);
        return $timestamp > 0 ? $data : "";
    }
}

if (!function_exists("show_message_alert")) {
    function show_message_alert($msg, $type = "success") {
        $CI = & get_instance();
        $CI->session->set_flashdata('msg', array("message"=>$msg, "type"=> $type));
    }
}

if (!function_exists("create_pagination")) {
    function create_pagination($url, $total_rows, $per_page = 10) {
        $CI = & get_instance();
        $CI->load->library('pagination');
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['enable_query_strings'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = "p";
        $config['base_url'] = $url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['full_tag_open'] = '<div class="ls-pagination-filter"><ul class=ls-pagination>';
        $config['full_tag_close'] = '</ul></div><!--pagination-->';
        
        $config['first_link'] = '&laquo; Primeiro';
        $config['first_tag_open'] = '<li class="ls-pagination">';
        $config['first_tag_close'] = '</li>';
        
        $config['last_link'] = 'Último &raquo;';
        $config['last_tag_open'] = '<li class="ls-pagination">';
        $config['last_tag_close'] = '</li>';
        
        $config['next_link'] = 'Próximo &rarr;';
        $config['next_tag_open'] = '<li class="ls-pagination">';
        $config['next_tag_close'] = '</li>';
        
        $config['prev_link'] = '&larr; Anterior';
        $config['prev_tag_open'] = '<li class="ls-pagination">';
        $config['prev_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="ls-active"><a href>';
        $config['cur_tag_close'] = '</a></li>';
        
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links();
    }
}

if (!function_exists("get_data_nascimento")) {
    function get_data_nascimento($data) {
        $CI = & get_instance();
        if (!empty($data) && $data != "0000-00-00") {
            $atual = date_create(date("Y-m-d"));
            $nascimento = date_create($data);
            $interval = date_diff($atual, $nascimento);
            return $interval->y;
        } 
        else {
            return "";
        }
    }
}

if (!function_exists("converte_data")) {
    function converte_data($data, $hours = false) {
        $timestamp = strtotime($data);
        if (!$hours) $data = date("d/m/Y", $timestamp);
        else $data = date("d/m/Y H:i:s", $timestamp);
        return $timestamp > 0 ? $data : "";
    }
}

if (!function_exists("convert_values_save")) {
    function convert_values_save($type, $data) {
        foreach ($type as $key => $value) {
            switch ($value) {
                case 'date':
                    foreach ($data as $key => $value) {
                        $is_date = substr_count($value, "/");
                        if ($is_date == 2) {
                            if (is_numeric(str_replace("/", "", $value))) {
                                $data[$key] = convert_date_save($value);
                            } 
                            else {
                                $data[$key] = $value;
                            }
                        } 
                        else {
                            $data[$key] = $value;
                        }
                    }
                    break;

                case 'money':
                    foreach ($data as $key => $value) {
                        if (!is_numeric($value)) {
                            $value = str_replace("R$", "", $value);
                            $value = str_replace(".", "", $value);
                            $value = str_replace(",", ".", $value);
                            $data[$key] = $value;
                        } 
                        else {
                            $data[$key] = $value;
                        }
                    }
                    break;
            }
        }
        return $data;
    }
}

if (!function_exists("money_convert_save")) {
    function money_convert_save($money) {
            $money = str_replace("R$", "", $money);
            $money = str_replace(".", "", $money);
            $money = str_replace(",", ".", $money);
            return $money;
    }
}

if (!function_exists("convert_money")) {
    function convert_money($value, $flag = true) {
        if ($flag) return "R$ " . number_format($value, 2, ',', '.');
        else return number_format($value, 2, ',', '.');
    }
}

if (!function_exists("convert_date_save")) {
    function convert_date_save($data, $hours = false, $isOldDate = false) {
        if (!$isOldDate) {
            $data = str_replace("/", "-", $data);
            $timestamp = strtotime($data);
            if (!$hours) $data = date("Y-m-d", $timestamp);
            else $data = date("Y-m-d H:i:s", $timestamp);
            
            return $timestamp > 0 ? $data : "";
        } 
        else {
            $data = explode("/", $data);
            return $data = $data[2] . "-" . $data[1] . "-" . $data[0];
        }
    }
}

if (!function_exists("format_data")) {
    function format_data($data = array(), $keys = array()) {
        foreach ($keys as $key => $value) {
            if (!empty($data[0]->$key)) {
                switch ($value) {
                    case 'data:hours':
                        foreach ($data as $k => $v) {
                            $data[$k]->$key = converte_data($v->$key, true);
                        }
                        break;

                    case 'data':
                        foreach ($data as $k => $v) {
                            $data[$k]->$key = converte_data($v->$key);
                        }
                        break;

                    case 'dinheiro':
                        foreach ($data as $k => $v) {
                            $data[$k]->$key = convert_money($v->$key);
                        }
                        break;

                    case 'fone':
                        foreach ($data as $k => $v) {
                            $data[$k]->$key = formatar_dados_pessoais($v->$key, "fone");
                        }
                        break;

                    case 'cep':
                        foreach ($data as $k => $v) {
                            $data[$k]->$key = formatar_dados_pessoais($v->$key, "cep");
                        }
                        break;

                    case 'cpf':
                        foreach ($data as $k => $v) {
                            $data[$k]->$key = formatar_dados_pessoais($v->$key, "cpf");
                        }
                        break;

                    default:
                        break;
                }
            }
        }
        return $data;
    }
}
if (!function_exists("get_nivel")) {
    function get_nivel($nivel = NULL, $super = false) {
        $niveis = array(
            "1" => "Administrador",
            "Financeiro",
            "Operacional"
        );
        if ($super)
            $niveis["-1"] = "SUPER ADMIN";
        if (empty($nivel)) {
            return $niveis;
        } else {
            return $niveis[$nivel];
        }
    }
}
if (!function_exists("formatar_dados_pessoais")) {
    function formatar_dados_pessoais($string, $tipo = "") {
        $string = preg_replace("[^0-9]", "", $string);
        if (!$tipo) {
            switch (strlen($string)) {
                case 10:
                    $tipo = 'fone';
                    break;

                case 8:
                    $tipo = 'cep';
                    break;

                case 11:
                    $tipo = 'cpf';
                    break;

                case 14:
                    $tipo = 'cnpj';
                    break;
            }
        }
        switch ($tipo) {
            case 'fone':
                $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) . '-' . substr($string, 6);
                break;

            case 'cep':
                $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);
                break;

            case 'cpf':
                $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) . '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
                break;

            case 'cnpj':
                $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) . '-' . substr($string, 12, 2);
                break;

            case 'rg':
                $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3);
                break;
        }
        return $string;
    }
}
if (!function_exists("generateGuid")) {
    function generateGuid($include_braces = false) {
        if (function_exists('com_create_guid')) {
            if ($include_braces === true) {
                return com_create_guid();
            } 
            else {
                return substr(com_create_guid(), 1, 36);
            }
        } 
        else {
            mt_srand((double)microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            
            $guid = substr($charid, 0, 8) . '-' . substr($charid, 8, 4) . '-' . substr($charid, 12, 4) . '-' . substr($charid, 16, 4) . '-' . substr($charid, 20, 12);
            
            if ($include_braces) {
                $guid = '{' . $guid . '}';
            }
            return $guid;
        }
    }
}

if (!function_exists("replace_character")) {
    function replace_character($string) {
        $replace = array('&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '', '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae', '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae', 'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D', 'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E', 'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G', 'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I', 'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K', 'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N', 'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O', 'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S', 'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T', 'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U', '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U', 'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z', 'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a', 'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c', 'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e', 'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h', 'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i', 'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j', 'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l', 'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n', 'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe', '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe', 'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u', 'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y', 'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss', 'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya');
        return str_replace(array_keys($replace), $replace, $string);
    }
}

if (!function_exists("unique_multidim_obj")) {
    function unique_multidim_obj($obj, $key, $scene = false) {
        $temp_obj = new stdClass;
        $key_obj = new stdClass;
        $i = 2;
        foreach ($obj as $k => $val) {
            if (!in_object($val->{$key}, $key_obj)) {
                $key_obj->{$k} = $val->{$key};
                $temp_obj->{$k} = $val;
                $i = 2;
            }
        }
        return $temp_obj;
    }
}
if (!function_exists("unique_multidim_array")) {
    function unique_multidim_array($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    }
}
if (!function_exists("in_object")) {
    function in_object($value, $object) {
        if (is_object($object)) {
            foreach ($object as $key => $item) {
                if ($value == $item) return $key;
            }
        }
        return false;
    }
}
if (!function_exists("recursive_array_search")) {
    function recursive_array_search($needle, $haystack) {
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
                return $value;
            }
        }
        return false;
    }
}
if (!function_exists("title_adjust")) {
    function title_adjust($str_title) {
        $temp = is_array($str_title) ? $str_title : explode("-",$str_title);
        
        $title = "";

        foreach ($temp as $v) {
            $title .= ucfirst(strtolower($v))." ";
        }
        return $title .= " - ".DEFAULT_TITLE;
    }
}
if (!function_exists("get_estados")){
    function get_estados() {
        $estados = array(
            "AC",
            "AL",
            "AM",
            "AP",
            "BA",
            "CE",
            "DF",
            "ES",
            "GO",
            "MA",
            "MG",
            "MS",
            "MT",
            "PA",
            "PB",
            "PE",
            "PI",
            "PR",
            "RJ",
            "RN",
            "RS",
            "RO",
            "RR",
            "SC",
            "SE",
            "SP",
            "TO"
        );
        return $estados;
    }
}
if(!function_exists("get_tipo_solicitacao")){
    function get_tipo_solicitacao($tipo = NULL, $flag = false) {
        $tipo_solicitacao = array(
            "I" => "IMPORTAÇÃO",
            "E" => "EXPORTAÇÃO",
            "T" => "TRANSFERÊNCIA",
            "DI" => "DI",
            "DTA" => "DTA",
            "CA" => "CABOTAGEM",
        );
        if (empty($tipo)) {
            if(!$flag)
                return $tipo_solicitacao;
            else
                return "";
        } else {
            return $tipo_solicitacao[$tipo];
        }
    }
}
if(!function_exists("get_ordem_coleta_nf")){
    function get_ordem_coleta_nf($data = array())
    {
        $CI = & get_instance();
        $CI->load->model("Ordem_coleta_nf_model","Ordem_coleta_nf",true);
        $nf = $CI->Ordem_coleta_nf->get($data);
        return $nf ? $nf : array();
    }
}
if(!function_exists("month_br")){
    function month_br($mes) {
        $mes_br = array("1" => "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
        return $mes_br[$mes];
    }
}
if(!function_exists("get_tipo_tomador")){
    function get_tipo_tomador($type = NULL) {
        $types = array(
            "1" => "Remetente",
            "Expedidor",
            "Recebedor",
            "Destinatario",
            "Outros"
        );
        if (empty($type)) {
            return $types;
        } else {
            return $types[$type];
        }
    }
}
if(!function_exists("removeEspecialCode")){
    function removeEspecialCode($str)
    {
        if (!is_array($str)) {
            return strtr(utf8_decode(html_entity_decode($str)), utf8_decode('ÀÁÃÂÉÊÍÓÕÔÚÜÇÑàáãâéêíóõôúüçñ'), 'AAAAEEIOOOUUCNaaaaeeiooouucn');
        } else {
            foreach ($str as $key => $value) {
                foreach ($value as $k => $v) {
                    $str[$key]->{$k} = strtr(utf8_decode(html_entity_decode($v)), utf8_decode('ÀÁÃÂÉÊÍÓÕÔÚÜÇÑàáãâéêíóõôúüçñ'), 'AAAAEEIOOOUUCNaaaaeeiooouucn');
                }
            }
            return $str;
        }
    }
}
if(!function_exists("chave_acesso")){
    function chave_acesso($cUF, $AAMM, $CNPJ, $mod, $serie, $nCT, $tpEmis, $cCT){
        /*
        · cUF - Código da UF do emitente do Documento Fiscal   2
        · AAMM - Ano e Mês de emissão do CT-e 4
        · CNPJ - CNPJ do emitente 14
        · mod - Modelo do Documento Fiscal 2
        · serie - Série do Documento Fiscal 3
        · nCT - Número do Documento Fiscal 9
        · tpEmis ? Forma de emissão do CT-e 1
        · cCT - Código Numérico que compõe a Chave de Acesso 8
        · cDV - Dígito Verificador da Chave de Acesso 1
        */
        // 02 - cUF  - código da UF do emitente do Documento Fiscal
        $chave = sprintf("%02d", $cUF);
        
        // 04 - AAMM - Ano e Mes de emissão da NF-e
        $chave .= sprintf("%04s", $AAMM);
        
        // 14 - CNPJ - CNPJ do emitente
        $chave .= sprintf("%014s", $CNPJ);
        
        // 02 - mod  - Modelo do Documento Fiscal
        $chave .= sprintf("%02d", $mod);
        
        // 03 - serie - Série do Documento Fiscal
        $chave .= sprintf("%03d", $serie);
        
        // 09 - nCT  - Número do Documento Fiscal
        $chave .= sprintf("%09d", $nCT);
        
        // 01 - tpEmis  - Tipo emissão 
        $chave .= sprintf("%01d", $tpEmis);
        
        // 08 - cCT  - Código Numérico que compõe a Chave de Acesso 
        $chave .= sprintf("%08d", $cCT);
        
        // 01 - cDV  - Dígito Verificador da Chave de Acesso
        $chave .= calcula_dv($chave);
        
        return $chave;
    }
}
if(!function_exists("calcula_dv")){
    function calcula_dv($chave43){
        $multiplicadores = array(
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9
        );
        $i               = 42;
        $soma_ponderada  = 0;
        while ($i >= 0) {
            for ($m = 0; $m < count($multiplicadores) && $i >= 0; $m++) {
                $soma_ponderada += $chave43[$i] * $multiplicadores[$m];
                $i--;
            }
        }
        $resto = $soma_ponderada % 11;
        if ($resto == '0' || $resto == '1') {
            $cDV = 0;
        } else {
            $cDV = 11 - $resto;
        }
        //$this->cDV = $cDV;
        return $cDV;
    }
}