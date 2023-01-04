<?php
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'cannex-soap.php';

    $wsdl = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'wsdl' . DIRECTORY_SEPARATOR . 'canx_antu-2.0.wsdl';
    $endpoint = 'https://wwwdev.cannex.com/devext/CANX/AntuService';

    // $wsdl = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'wsdl' . DIRECTORY_SEPARATOR . 'canx_anty_anly-1.0.wsdl';
    // $endpoint = 'https://wwwdev.cannex.com/devext/CANX/AntyAnlyService';

    $client = new CANNEX_SOAP_Test( $wsdl, $endpoint );

    if ( ( !empty( $_POST ) ) && ( isset( $_POST[ 'app' ] ) ) ) :
        $client->query( $_POST, 'canx_antu_operation' );
    endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>

        <link rel="stylesheet" type="text/css" href="../assets/css/theme.css" media="all">
    </head>
    <body>
        <main id="alpha" class="alpha">
            <input type="checkbox" id="alpha__prologue-collapse" class="alpha__prologue-collapse" value="1">
            <label class="alpha__prologue-collapse-control" for="alpha__prologue-collapse">Collapse</label>

            <header class="alpha__prologue">
                <section class="alpha__prologue-primary">
                    <a href="#/" class="alpha__prologue-primary-logo"><span class="title">Alpha</span> by Annuity Association</a>

                    <nav>
                        <ol>
                            <li>
                                <a href="#/income-solver">Income Solver</a>

                                <ol>
                                    <li><a href="#">Option 1</a></li>
                                    <li><a href="#">Option 2</a></li>
                                    <li><a href="#">Option 3</a></li>
                                </ol>
                            </li>
                            <li>
                                <a href="#/performance">Performance</a>

                                <ol>
                                    <li><a href="#">Option 1</a></li>
                                    <li><a href="#">Option 2</a></li>
                                    <li><a href="#">Option 3</a></li>
                                </ol>
                            </li>
                            <li>
                                <a href="#/research">Research</a>

                                <ol>
                                    <li><a href="#">Option 1</a></li>
                                    <li><a href="#">Option 2</a></li>
                                    <li><a href="#">Option 3</a></li>
                                </ol>
                            </li>
                            <li>
                                <a href="#/reporting">Reporting</a>

                                <ol>
                                    <li><a href="#">Option 1</a></li>
                                    <li><a href="#">Option 2</a></li>
                                    <li><a href="#">Option 3</a></li>
                                </ol>
                            </li>
                        </ol>
                    </nav>
                </section>

                <section class="alpha__prologue-secondary">
                    <aside class="alpha__prologue-secondary-client">
                        <h3>Test User <span class="money">$100,000</span></h3>
                        <menu class="stats">
                            <li data-type="location">Florida</li>
                            <li data-type="age">65</li>
                            <li data-type="marital">Single</li>
                        </menu>
                        <menu class="controls">
                            <li data-type="change"><a href="#/change" title="Change Client">Change Client</a></li>
                            <li data-type="edit"><a href="#/edit" title="Edit Client">Edit Client</a></li>
                            <li data-type="add"><a href="#/add" title="Add Client">Add Client</a></li>
                        </menu>
                    </aside>

                    <aside class="alpha__prologue-secondary-search">
                        <details>
                            <summary>Search</summary>
                            <div class="alpha__prologue-secondary-search-ui">
                                <div class="alpha__prologue-secondary-search-ui-inner">
                                    <label for="alpha__prologue-secondary-search-input">Search</label>
                                    <input type="text" id="alpha__prologue-secondary-search-input" class="alpha__prologue-secondary-search-input" name="query" placeholder="Search">
                                </div>
                            </div>
                        </details>
                    </aside>

                    <aside class="alpha__prologue-secondary-user">
                        <h3>Welcome, User! <i class="fal fa-hand-wave" aria-hidden="true"></i> </h3>
                        <ol>
                            <li><a href="#/profile">Profile</a></li>
                            <li><a href="#/logout">Logout</a></li>
                        </ol>
                    </aside>
                </section>
            </header>
            <article>
                <header>
                    <h1>CANNEX Integration Test</h1>
                </header>

                <section>
                    <div class="messages">
                        <?php foreach ( $client->messages as $message ) : ?>
                            <?php if ( !empty( $message ) ) : ?>
                            <div class="message message--warning">
                                <p><strong>CANNEX Message:</strong></p>
                                <p><?php echo $message; ?></p>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <form action="./" method="POST" class="parameters">
                        <input type="hidden" name="app" value="CANX">

                        <fieldset>
                            <legend>Search Parameters</legend>

                            <div class="messages">
                                <div class="message message--info message--small">
                                    <p><strong>This page solves for the following client data:</strong></p>
                                    <dl>
                                        <dt><i class="fal fa-location-pin" aria-hidden="true"></i></dt><dd>Florida</dd>
                                        <dt><i class="fal fa-calendar" aria-hidden="true"></i></dt><dd>Born on 12/30/1944</dd>
                                        <dt><i class="fal fa-rings-wedding" aria-hidden="true"></i></dt><dd>Married</dd>
                                    </dl>
                                    <p><small>Client variables will be editable via the client information widget at the top of the screen in the next build.</small></p>
                                </div>
                            </div>

                            <input type="checkbox" name="mode" id="mode" value=""<?php if ( isset( $_POST[ 'mode' ] ) ) : ?> checked<?php endif; ?>>

                            <div class="fields">
                                <div class="fields__inner">
                                    <div class="fields__field">
                                        <label>Mode</label>

                                        <ul class="fields__field--radio">
                                            <li data-type="premium"><label for="mode"><strong>Solving for Monthly Income</strong>  Change to Solve for Premium?</label></li>
                                            <li data-type="income"><label for="mode"><strong>Solving for Premium</strong>  Change to Solve for Monthly Income?</label></li>
                                        </ul>
                                    </div>

                                    <div class="fields__group">
                                        <div class="fields__field" data-type="income">
                                            <label for="investment">Investment Amount</label>
                                            <input type="text" name="investment" id="investment" value="<?php echo ( ( isset( $_POST[ 'investment' ] ) ) ? $_POST[ 'investment' ] : '$100,000.00' ); ?>" placeholder="i.e., $100,000.00">
                                        </div>
                                        <div class="fields__field" data-type="premium">
                                            <label for="monthly">Desired Monthly Income</label>
                                            <input type="text" name="monthly" id="monthly" value="<?php echo ( ( isset( $_POST[ 'monthly' ] ) ) ? $_POST[ 'monthly' ] : '$500.00' ); ?>" placeholder="i.e., $1,000.00">
                                        </div>
                                        <div class="fields__field">
                                            <label for="deferral">Defer Income for <strong><?php echo ( ( isset( $_POST[ 'deferral' ] ) ) ? intval( $_POST[ 'deferral' ] ) : 10 ); ?> years</strong></label>
                                            <input type="range" name="deferral" step="1" min="5" max="20" value="<?php echo ( ( isset( $_POST[ 'deferral' ] ) ) ? intval( $_POST[ 'deferral' ] ) : 10 ); ?>">
                                        </div>
                                        <div class="fields__field">
                                            <button type="submit">Query CANNEX</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>

                    <?php if ( !empty( $client->result ) ) : ?>
                    <div class="results">
                        <h3 class="inline">Results for <?php echo $client->request[ 'canx_antu_operation' ][ 'guarantee_year' ]; ?>-year deferral</h3>

                        <div class="results__inner">
                            <div class="result">
                                <h4>
                                    <strong><?php echo $client->result->institution_name; ?></strong>
                                    &ldquo;<?php echo $client->result->quote->product_name; ?>&rdquo;
                                </h4>

                                <div class="result__grid">
                                    <div class="result__grid-cta">
                                        <div class="result__grid-cta-financials">
                                            <?php if ( isset( $_POST[ 'mode' ] ) ) : ?>
                                            <span class="money">$<?php echo money_format( $client->result->quote->income, 2 ); ?></span>
                                            <span><strong>per month</strong></span>
                                            <?php else : ?>
                                            <span class="money">$<?php echo money_format( $client->result->quote->premium, 2 ); ?></span>
                                                <span><strong>required premium</strong> to return $<?php echo money_format( $client->result->quote->income, 2 ); ?> <strong>per month</strong></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="result__grid-cta-info">
                                            <h5>Product Details</h5>

                                            <p>Proin augue augue, dictum eu urna id, vulputate luctus tortor.</p>

                                            <dl>
                                                <dt>Company</dt>
                                                <dd><?php echo $client->result->institution_name; ?> (NAIC #<?php echo $client->result->naic; ?>)</dd>

                                                <dt>Product</dt>
                                                <dd><?php echo $client->result->quote->product_name; ?></dd>

                                                <dt>Rating</dt>
                                                <dd><?php echo $client->result->quote->rating; ?></dd>

                                                <dt>Deferral Period</dt>
                                                <dd><?php echo $client->request[ 'canx_antu_operation' ][ 'guarantee_year' ]; ?> years</dd>

                                                <dt>Premium</dt>
                                                <dd>$<?php echo money_format( $client->result->quote->premium, 2 ); ?></dd>

                                                <dt>Monthly Income</dt>
                                                <dd>$<?php echo money_format( $client->result->quote->income, 2 ); ?></dd>

                                                <dt>Tax Amount</dt>
                                                <dd>$<?php echo money_format( $client->result->quote->tax_amount, 2 ); ?></dd>

                                                <dt>Return of Premium</dt>
                                                <dd><?php echo $client->result->quote->return_of_premium; ?></dd>

                                                <dt>LSIN</dt>
                                                <dd><?php echo $client->result->quote->lsin; ?></dd>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="result__grid-illustration">
                                        <div class="result__grid-illustration-inner"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </section>
            </article>
        </main>

        <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/carbon-components@latest/scripts/carbon-components.js"></script>
        <script>
            jQuery( document ).ready(
                function() {
                    jQuery( 'input[type="range"]' ).on( 'change', function( event ) {
                        let element = jQuery( this );
                        let label = element.siblings( 'label' ).find( 'strong' ).text( element.val() + ' years' );
                    } );
                }
            )
        </script>
    </body>
</html>