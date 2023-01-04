<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Platform Login &mdash; ALPHA') }}</title>

        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset( 'assets/css/theme.css' ) }}">
        @vite(['resources/js/app.js'])
    </head>
    <body>
        <main id="alpha" class="alpha">
            <input type="checkbox" id="alpha__prologue-collapse" class="alpha__prologue-collapse" value="1">
            <label class="alpha__prologue-collapse-control" for="alpha__prologue-collapse">Collapse</label>

            <header class="alpha__prologue">
                <section class="alpha__prologue-primary">
                    <a href="{{ route( 'home' ) }}" class="alpha__prologue-primary-logo"><span class="title">Alpha</span> by Annuity Association</a>

                    <nav>
                        <ol>
                            <li>
                                <a href="#/income-solver">Income Solver</a>

                                <ol>
                                    <li><a href="#">Guaranteed Rider</a></li>
                                    <li><a href="#">Advanced Rider</a></li>
                                    <li><a href="#">SPIA/DIA</a></li>
                                    <li><a href="#">Illustrations</a></li>
                                </ol>
                            </li>
                            <li>
                                <a href="#/performance">Performance</a>

                                <ol>
                                    <li><a href="#">Annuity Analyzer</a></li>
                                    <li><a href="#">Backtesting</a></li>
                                    <li><a href="#">Top Products</a></li>
                                </ol>
                            </li>
                            <li>
                                <a href="#/research">Research</a>

                                <ol>
                                    <li><a href="#">Index Tracker & History</a></li>
                                    <li><a href="#">Annuity Products Data</a></li>
                                </ol>
                            </li>
                            <li>
                                <a href="#/reporting">Reporting</a>

                                <ol>
                                    <li><a href="#">TBD</a></li>
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
                            <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        </ol>
                        <form id="logout-form" action="{{ route( 'logout' ) }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </aside>
                </section>
            </header>
            <article>
                @yield( 'content' )
            </article>
        </main>
    </body>
</html>
