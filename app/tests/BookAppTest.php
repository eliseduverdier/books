<?php

namespace App\Tests;

use App\Tests\Dom\Dom;
use App\Tests\Util\Assert;
use App\Tests\Util\FunctionalTestCase;

class BookAppTest extends FunctionalTestCase
{
    protected Dom $dom;

    public function setUp(): void
    {
        parent::setUp();
        $this->dom = new Dom();
    }

    public function testHomeList(): void
    {
        $this->dom->loadStr($this->crawl('/'));

        // Page title
        Assert::equals('List of read books 📚', $this->dom->findOne('h1')->innerText);

        // Login form
        Assert::equals('Login', $this->dom->findOne('#auth')->innerText);

        // Items
        Assert::count(4, $this->dom->find('li.books-list__item'));
        Assert::isNull($this->dom->findOne('li#title1_author1')); // is private
        Assert::exists($this->dom->findOne('li#title2_author1'));
        Assert::exists($this->dom->findOne('li#title3_author2'));
        Assert::exists($this->dom->findOne('li#title4_author2'));
        Assert::isNull($this->dom->findOne('li#title5_author2')); // deleted

        // Values
        Assert::equals('Title 3', $this->dom->findOne('#title3_author2 .title-item')->innerText);
        Assert::equals('?slug=title3_author2&action=show', $this->dom->findOne('#title3_author2 .title-item a')->getAttribute('href'));
        Assert::equals('Author #2', $this->dom->findOne('#title3_author2 .author-item')->innerText);
        Assert::equals('?filter[author]=author2', $this->dom->findOne('#title3_author2 .author-item a')->getAttribute('href'));
        Assert::equals('currently reading', $this->dom->findOne('#title3_author2 .finished-at-item')->innerText);
        Assert::equals('abandonned', $this->dom->findOne('#title4_author2 .finished-at-item')->innerText);
    }

    public function testHomeListLoggedIn(): void
    {
        $this->dom->loadStr($this->crawl('/', 'GET', [], true));

        // Login form
        Assert::equals('Logout', $this->dom->findOne('#auth')->innerText);

        // Items
        Assert::count(6, $this->dom->find('li.books-list__item'));
        Assert::exists($this->dom->findOne('li#title1_author1'));
        Assert::equals('🔑 Title 1', $this->dom->findOne('li#title1_author1 .title-item')->innerText);
        Assert::equals('🗝 Title 3', $this->dom->findOne('li#title3_author2 .title-item')->innerText);
    }

    public function testCreation(): void
    {
        // Create book, existing author
        $output = $this->crawl('/', 'POST', [
            'title' => 'New Book',
            'author' => 'author1',
            'type' => 'novel',
            'note' => '1',
            'finished_at' => '2022-01-01',
        ], true);
        $this->dom->loadStr($output);

        // items
        Assert::exists($this->dom->findOne('li#author1_new_book'));
        // attributes
        Assert::equals('New Book', $this->dom->findOne('li#author1_new_book .title-item')->innerText);
        Assert::equals('Author #1', $this->dom->findOne('li#author1_new_book .author-item')->innerText);
        Assert::equals('novel', $this->dom->findOne('li#author1_new_book .type-item')->innerText);
        Assert::equals('**', $this->dom->findOne('li#author1_new_book .note-item')->innerText);
        Assert::equals('2022⋅01⋅01', $this->dom->findOne('li#author1_new_book .finished-at-item')->innerText);
    }

    // Create book, unlogged user
    public function testCreateUnlogged(): void
    {
        $output = $this->crawl('/', 'POST', ['title' => 'Nope', 'author' => 'Nope',]);
        $this->dom->loadStr($output);
        Assert::isNull($this->dom->findOne('li#nope_nope'));
    }

    // No title: no book
    public function testCreateNoTitle(): void
    {
        $output = $this->crawl('/', 'POST', ['author' => 'Nope',]);
        $this->dom->loadStr($output);
        Assert::isNull($this->dom->findOne('li#nope_'));
    }

    // Create unoted/untyped
    public function testCreationNewAuthor(): void
    {
        // Form to create book, existing author
        $output = $this->crawl('/', 'POST', [
            'title' => 'New Book with New Author',
            'author' => 'Author 3',
        ], true);
        $this->dom->loadStr($output);

        Assert::exists($this->dom->findOne('li#author_3_new_book_with_new_author'));
        Assert::equals('Author 3', $this->dom->findOne('li#author_3_new_book_with_new_author .author-item')->innerText);
        Assert::equals('currently reading', $this->dom->findOne('li#author_3_new_book_with_new_author .finished-at-item')->innerText);
    }

    public function testFilter(): void
    {
        $output = $this->crawl('/?filter[note]=2');
        $this->dom->loadStr($output);

        Assert::count(3, $this->dom->find('li.books-list__item'));
        Assert::equals('***', $this->dom->findOne('li#title3_author2 .note-item')->innerText);
        Assert::equals('***', $this->dom->findOne('li#title4_author2 .note-item')->innerText);
    }

    public function testSort(): void
    {
        $output = $this->crawl('/?sort[]=note', 'GET', [], true);
        $this->dom->loadStr($output);

        Assert::equals('title3_author2', $this->dom->find('li.books-list__item')[2]->getAttribute('id'));
        Assert::equals('title4_author2', $this->dom->find('li.books-list__item')[3]->getAttribute('id'));
        Assert::equals('author1_new_book', $this->dom->find('li.books-list__item')[4]->getAttribute('id'));
        Assert::equals('title2_author1', $this->dom->find('li.books-list__item')[5]->getAttribute('id'));
        Assert::equals('title1_author1', $this->dom->find('li.books-list__item')[6]->getAttribute('id'));
        Assert::equals('author_3_new_book_with_new_author', $this->dom->find('li.books-list__item')[7]->getAttribute('id'));
    }


    public function testSee(): void
    {
        $this->dom->loadStr($this->crawl('/?action=show&slug=title1_author1'));
        Assert::equals('📖 Title 1', $this->dom->findOne('h1')->innerHtml);
    }


    // TODO
    public function testEditForm(): void
    {
//        $output = $this->crawl('/?action=edit&slug=title1_author1', 'GET', [], true);
    }

    public function testEdit(): void
    {
//        $output = $this->crawl('/?action=edit', 'POST', [], true);
    }

    public function testDelete(): void
    {
    }
}
