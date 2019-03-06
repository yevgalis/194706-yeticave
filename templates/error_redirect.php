<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $value): ?>
            <li class="nav__item">
                <a href="all-lots.php?category_id=<?=$value['category_id']; ?>"><?=$value['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?=$error_title; ?></h2>
    <p><?=$error_text; ?></p>
</section>
