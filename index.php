<?php

declare(strict_types=1);

require __DIR__ . "/header.php";
// require __DIR__ . "/data-source.php";
require __DIR__ . "/data-generated.php";


// sort books based on form input:
if (isset($_GET["sort"], $_GET["ascOrDesc"])) {
  $sortBy = htmlspecialchars($_GET["sort"]);
  $sortAscOrDesc = htmlspecialchars($_GET["ascOrDesc"]);
}

$sortingKey = array_column($books, $sortBy);
if ($sortAscOrDesc === "asc") {
  array_multisort($sortingKey, SORT_ASC, $books);
} elseif ($sortAscOrDesc === "desc") {
  array_multisort($sortingKey, SORT_DESC, $books);
}

// filter books based on search(author and title):
$filteredBooks = $books;

if (isset($_GET["filterBy"])) {
  $filterBy = trim(htmlspecialchars(strtolower($_GET["filterBy"])));
  $filteredBooks = array_filter($books, function ($var) use ($filterBy) {
    if (
      str_contains(strtolower($var["title"]), $filterBy) ||
      str_contains(strtolower($var["author"]), $filterBy)
    ) {
      return 1;
    }
  });
  // var_export($filteredBooks);
}

?>

<main>
  <section class="info-section">
    <!-- Display the selected book: -->
    <div class="selection">
      <?php if (isset($_GET["id"])) :
        $selectedBook = $_GET["id"]; ?>
        <p>Title: <?= $books[$selectedBook]["title"]; ?></p>
        <p>Author: <?= $books[$selectedBook]["author"]; ?></p>
        <p>Genre: <?= $books[$selectedBook]["genre"]; ?></p>
        <p>Release year: <?= $books[$selectedBook]["year"]; ?></p>
        <p>Number of pages: <?= $books[$selectedBook]["pages"]; ?></p>
      <?php else : ?>
        <p>Do one of the following:</p>
        <ul>
          <li>Pick a book</li>
          <li>Sort the books</li>
          <li>Search for a book</li>
        </ul>
      <?php endif; ?>
    </div>

    <div class="sort-and-filter">
      <!-- select which parameter to sort by alphabetically. ASC or DESC.
      Color currently sorts by hex value... not ideal, might fix later. -->
      <div class="sort">
        <form action="index.php" method="get">
          <label for="sort">Sort by:</label><br>
          <select id="sort" name="sort">
            <option value="title" selected>Title</option>
            <option value="author">Author</option>
            <option value="pages">Pages</option>
            <option value="year">Release year</option>
            <option value="color">Color of book</option>
          </select>
          <button type="submit" value="asc" name="ascOrDesc">Asc</button>
          <button type="submit" value="desc" name="ascOrDesc">Desc</button>
        </form>
      </div>


      <!-- Search and filter by title or author: -->
      <div class="filter">
        <form action="index.php" method="get">
          <label for="filterBy">Search title or author:</label><br>
          <input type="text" name="filterBy">
          <button type="submit">OK</button>
        </form>
      </div>
    </div>
  </section>

  <section class="bookshelf">
    <form action="index.php" method="get" class="form-bookshelf">
      <?php foreach ($books as $book) : ?>
        <?php foreach ($filteredBooks as $filteredBook) :
          if ($filteredBook == $book) : ?>

            <!-- Each book is a button and can be selected: -->
            <button type="submit" value="<?= $book["id"] ?>" name="id" class="book" style="
            background-color: <?= $book["color"] ?>;
            font-family: <?= $book["fonts"] ?> , sans-serif;
            width: <?= $book["pages"] / 4 ?>px;">
              <p><?= $book["title"] ?></p>
              <p><?= $book["author"] ?></p>
            </button>

          <?php endif; ?>
        <?php endforeach; ?>
      <?php endforeach ?>
    </form>
  </section>
</main>

<?php

// echo "<pre>";
// var_export($books);
?>

</body>

</html>