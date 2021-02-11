<table class="table">
  <caption class="table__caption">библиотека</caption>
  <tbody>
  <?php foreach ($records as $book) { ?>
    <tr>
      <?php foreach ($book as $value) { ?>
        <td class="table__data"><?= $value; ?></td>
      <?php } ?>
    </tr>
  <?php } ?>
  </tbody>
</table>
