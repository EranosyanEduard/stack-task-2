<?php
require_once './mvc-core/View.php';

class Controller
{
    private mysqli $db;
    private object $model;
    private array $model_attributes;
    private array $page_templates;
    private string $current_page_template;

    public static function renderPageWithTip($tip, $res_code = 200)
    {
        http_response_code($res_code);
        View::render('page-with-tip.php', ['tip' => $tip]);
    }

    public function __construct($db, $model_class, $model_attributes, $page_templates)
    {
        $this->db = $db;
        $this->model = new $model_class($db);
        $this->model_attributes = $model_attributes;
        $this->page_templates = array_merge(
            ['fallback' => 'page-with-tip.php'],
            $page_templates
        );
        $this->current_page_template = '';
    }

    public function getRecords()
    {
        $query_result = $this->model->selectAllRecords();
        $this->renderPage($query_result);
    }

    public function getInsertId()
    {
        return mysqli_insert_id($this->db);
    }

    public function postRecord($req_params = null)
    {
        $req_params = is_array($req_params) ? $req_params : $_POST;
        if ($this->isValidRequestParams($req_params, $this->model_attributes)) {
            $query_string = $this->getQueryString($req_params);
            $query_result = $this->model->insertRecord($query_string);
            $this->renderPage($query_result);
        } else {
            self::renderPageWithTip('query contains invalid params', 400);
        }
    }

    public function removeRecord()
    {
        $required_param = 'id';
        if ($this->isValidRequestParams($_REQUEST, [$required_param])) {
            $query_result = $this->model->deleteRecord($_REQUEST[$required_param]);
            $this->renderPage($query_result);
        } else {
            self::renderPageWithTip('query contains invalid params', 400);
        }
    }

    public function updateRecord()
    {
        $required_param = 'id';
        if (array_key_exists($required_param, $_REQUEST)
            && count($_REQUEST) > 1
            && $this->isValidRequestParams(
                $_REQUEST, array_merge($this->model_attributes, [$required_param]), false
            )) {
            $filtered_req_params = array_filter($_REQUEST, function ($_, $field_name) {
                return $field_name !== 'id';
            }, 1);
            $query_string = $this->getQueryString($filtered_req_params);
            $query_result = $this->model->updateRecord($_REQUEST[$required_param], $query_string);
            $this->renderPage($query_result);
        } else {
            self::renderPageWithTip('query contains invalid params', 400);
        }
    }

    public function updatePageTemplate($template_id)
    {
        $this->current_page_template = $this->page_templates[$template_id];
    }

    private function isValidRequestParams($request_params, $required_params, $use_strict = true): bool
    {
        $total_different_params = count(array_diff(
            array_keys($request_params), $required_params
        ));
        return $use_strict && !$total_different_params
            ? count($request_params) === count($required_params)
            : !$total_different_params;
    }

    private function renderPage($query_result)
    {
        if ($query_result) {
            $page_props = is_bool($query_result)
                ? null
                : ['records' => mysqli_fetch_all($query_result, MYSQLI_ASSOC)];
            View::render($this->current_page_template, $page_props);
        } else {
            http_response_code(500);
            $error_props = [
                'code' => mysqli_errno($this->db),
                'message' => mysqli_error($this->db)
            ];
            View::render('page-with-error.php', $error_props);
        }
    }

    private function getQueryString($req_params): string
    {
        $get_query_expression = function ($field_name, $field_value) {
            return "$field_name = '" . strtolower($field_value) . "'";
        };
        return join(',', array_map(
            $get_query_expression,
            array_keys($req_params),
            array_values($req_params)
        ));
    }
}
