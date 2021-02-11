<?php

class Controller
{
  private $db_link;
  private $model;
  private array $model_fields;
  private string $page_template;

  public static function renderPageWithTooltip($tooltip, $res_code = 200)
  {
    http_response_code($res_code);
    View::render('page-with-tooltip.php', ['tooltip' => $tooltip]);
  }

  public function __construct($db_link, $model, $model_fields = [])
  {
    $this->db_link = $db_link;
    $this->model = new $model($db_link);
    $this->model_fields = $model_fields;
    $this->page_template = '';
  }

  public function getRecords()
  {
    $query_result = $this->model->selectAllRecords();
    $this->renderPage($query_result);
  }

  public function getInsertId()
  {
    return mysqli_insert_id($this->db_link);
  }

  public function postRecord($req_params = null)
  {
    $req_params = is_array($req_params) ? $req_params : $_POST;
    if ($this->isValidRequestParams($req_params, $this->model_fields)) {
      $query_string = $this->getQueryString($req_params);
      $query_result = $this->model->insertRecord($query_string);
      $this->renderPage($query_result);
    } else {
      self::renderPageWithTooltip('query contains invalid params', 400);
    }
  }

  public function removeRecord()
  {
    $required_param = 'id';
    if ($this->isValidRequestParams($_REQUEST, [$required_param])) {
      $query_result = $this->model->deleteRecord($_REQUEST[$required_param]);
      $this->renderPage($query_result);
    } else {
      self::renderPageWithTooltip('query contains invalid params', 400);
    }
  }

  public function updateRecord()
  {
    $required_param = 'id';
    if (array_key_exists($required_param, $_REQUEST)
      && count($_REQUEST) > 1
      && $this->isValidRequestParams(
        $_REQUEST, array_merge($this->model_fields, [$required_param]), false
      )) {
      $filtered_req_params = array_filter($_REQUEST, function ($_, $field_name) {
        return $field_name !== 'id';
      }, 1);
      $query_string = $this->getQueryString($filtered_req_params);
      $query_result = $this->model->updateRecord($_REQUEST[$required_param], $query_string);
      $this->renderPage($query_result);
    } else {
      self::renderPageWithTooltip('query contains invalid params', 400);
    }
  }

  public function updatePageTemplate($page_template)
  {
    $this->page_template = $page_template;
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
      View::render($this->page_template, $page_props);
    } else {
      http_response_code(500);
      $error_props = [
        'code' => mysqli_errno($this->db_link),
        'message' => mysqli_error($this->db_link)
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
