<div id="smartpolis_admin_options_page">
  <h1>Страховые компании</h1>
  <form id="smartpolis_admin_options_form" method="post">
    <div>
      <select name="action">
        <option value="">Выберите действие</option>
        <option value="update">Обновить справочники</option>
        <option value="save">Сохранить изменения</option>
      </select>
      <button type="submit" value="Выполнить">Выполнить</button>
    </div>
    <div>
<?php
  $companies = array();
  include_once( SMARTPOLIS_PLUGIN_DIR . 'php/smartpolis.class.settings.php' );
  if ( class_exists( 'smartpolisSettings' ) ) {
    $settings = new smartpolisSettings();
    if ( $settings->checkConnectionsParams() ) {
      if ( isset($_POST['action']) && $_POST['action']=='update' ) {

        try {
          $settings->updateCompanies();
        } catch (Exception $e) {
          echo '<h2>Ошибка обновления: ',  $e->getMessage(), "</h2>";
        }
      }

      else if ( isset($_POST['action']) && $_POST['action']=='save' ) {
        try {
          $settings->saveCompanies();
        } catch (Exception $e) {
          echo '<h2>Ошибка сохранения:',  $e->getMessage(), "</h2>";
        }
      }
      else if ( isset($_POST['action']) && $_POST['action']=='' ) {
        echo '<h2>Выберите действие</h2>';
      }
      $companies = $settings->getCompanies();
    } else {
      echo '<h2>Не настроены параметры соединения с сервером!</h2>';
    }
  } else {
    echo '<h2>Не удалось загрузить класс работы с настройками!</h2>';
  }

  if ( count($companies) == 0 ) {
    echo '<h2>Необходимо обновить справочники!</h2>';
  } else {
    echo '<table>';
    foreach($companies as $id => $company) {
      $active = $company['params']['active']=='true';
      echo '<tr>';
      echo '<td><input type="checkbox" name="smartpolis_companies['.$id.'][params][active]" value="true" '.($active?' checked':'').'/></td>';
      echo '<td><img src="http://casco.cmios.ru/'. $company['object']->logo .'" /></td>';
      echo '<td>' . $company['object']->title . '</td>';
      echo '<td><input type="text" name="smartpolis_companies['.$id.'][params][discount]" value="' . $company['params']['discount'] . '" /></td>';
      echo '<td>' . (($active)?'Активна':'Отключена') . '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }
?>
    </div>
  </form>
</div>
