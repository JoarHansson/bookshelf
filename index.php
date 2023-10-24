<?php

declare(strict_types=1);

require __DIR__ . "/header.php";
require __DIR__ . "/data.php";

?>

<div class="bookshelf">
  <form action="index.php" method="get">
    <?php foreach ($books as $key => $book) : ?>
      <button type="submit" value="<?= $book["id"] ?>" name="id" class="book" style="
        background-color: <?= $book["color"] ?>;
        width: <?= $book["pages"] / 4 ?>px;
      ">
        <p class="rotate" style="transform: rotate(90deg);">test</p>
      </button>

    <?php endforeach ?>
  </form>
  <div class="legs">
    <div class="leg"></div>
    <div class="leg"></div>
    <div class="leg"></div>
  </div>
</div>


<div class="selection">
  <?php
  if (isset($_GET["id"])) :
    $selection = $_GET["id"] ?>
    <p>Title: <?= $books[$selection]["title"]; ?></p>
    <p>Author: <?= $books[$selection]["author"]; ?></p>
    <p>Genre: <?= $books[$selection]["genre"]; ?></p>
    <p>Release year: <?= $books[$selection]["year"]; ?></p>
    <p>Number of pages: <?= $books[$selection]["pages"]; ?></p>

  <?php endif; ?>
</div>

</body>

</html>