<?php

declare(strict_types=1);

require __DIR__ . "/header.php";
require __DIR__ . "/data.php";

if (isset($_GET["sort"])) :
  $sortBy = $_GET["sort"]; ?>
  <p><?= $sortBy; ?></p>
<?php endif;

$sortingKey = array_column($books, $sortBy);
array_multisort($sortingKey, SORT_ASC, $books);

?>

<div class="bookshelf">
  <form action="index.php" method="get" class="form-bookshelf">
    <?php foreach ($books as $key => $book) : ?>

      <button type="submit" value="<?= $book["id"] ?>" name="id" class="book" style="
        background-color: <?= $book["color"] ?>;
        width: <?= $book["pages"] / 4 ?>px;">
        <p><?= $book["title"] ?></p>
        <p><?= $book["author"] ?></p>
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
  <?php if (isset($_GET["id"])) :
    $selectedBook = $_GET["id"]; ?>
    <p>Title: <?= $books[$selectedBook]["title"]; ?></p>
    <p>Author: <?= $books[$selectedBook]["author"]; ?></p>
    <p>Genre: <?= $books[$selectedBook]["genre"]; ?></p>
    <p>Release year: <?= $books[$selectedBook]["year"]; ?></p>
    <p>Number of pages: <?= $books[$selectedBook]["pages"]; ?></p>
  <?php endif; ?>
</div>

<div class="sort">
  <form action="index.php" method="get">
    <label for="sort">sort by:</label>
    <select id="sort" name="sort">
      <option value="title" selected>Title</option>
      <option value="author">Author</option>
      <option value="pages">Pages</option>
      <option value="year">Release year</option>
      <option value="color">Color of book</option>
    </select>
    <button type="submit">OK</button>
  </form>
</div>

<?php

echo "<pre>";
var_export($books);
?>

</body>

</html>