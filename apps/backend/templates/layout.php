<!DOCTYPE html>
<html lang="de">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
  </head>
  <body>
    <header>
      <h1><?php echo sfConfig::get('app_title') ?></h1>
    </header>
    <div id="wrapper">
        <nav class="width24">
            <ul>
              <li class="basic"><strong>Basic:</strong></li>
              <li><?php echo link_to('Assistant', 'assistant/index') ?></li>
              <li><?php echo link_to('Monitor', 'monitor/index') ?></li>
              <li class="advanced"><strong>Advanced:</strong></li>
              <li><?php echo link_to('Sessions', 'session') ?></li>
              <li><?php echo link_to('Participants', 'participant') ?></li>
              <li><?php echo link_to('Auctions', 'auction') ?></li>
              <li><?php echo link_to('Bidders', 'bidder') ?></li>
              <li><?php echo link_to('Bots', 'bot') ?></li>
              <li><?php echo link_to('Bids', 'bid') ?></li>
              <li class="last"><?php echo link_to('Logout', 'default/logout') ?></li>
            </ul>
        </nav>

        <?php if ($sf_user->hasFlash('error')): ?>
        <section class="width24">
            <article class="box error">
                <header>
                    <h2>Error</h2>
                </header>
                <?php echo $sf_user->getFlash('error') ?>
            </article>
        </section>
        <?php endif ?>

        <?php if ($sf_user->hasFlash('notice')): ?>
        <section class="width24">
            <article class="box notice">
                <header>
                    <h2>Notice</h2>
                </header>
                <?php echo $sf_user->getFlash('notice') ?>
            </article>
        </section>
        <?php endif ?>
      
        <section>
            <?php echo $sf_content ?>
        </section>
      
        <footer>
            Bachelor-Arbeit, Florian Stallmann, Universität Paderborn, Information Management &amp; E-Finance
        </footer>
    </div>
      
    <?php include_javascripts() ?>
  </body>
</html>
