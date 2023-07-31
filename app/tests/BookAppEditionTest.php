<?php

namespace App\Tests;

use App\Tests\Dom\Dom;
use App\Tests\Util\Assert;
use App\Tests\Util\FunctionalTestCase;

class BookAppEditionTest extends FunctionalTestCase
{
    protected Dom $dom;

    public function setUp(): void
    {
        parent::setUp();
        $this->dom = new Dom();
    }

    /**
     * Creation
     */

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
        Assert::equals('', $this->dom->findOne('li#author_3_new_book_with_new_author .type-item')->innerText);
        Assert::equals('', $this->dom->findOne('li#author_3_new_book_with_new_author .note-item')->innerText);
        Assert::equals('currently reading', $this->dom->findOne('li#author_3_new_book_with_new_author .finished-at-item')->innerText);
    }

    /**
     * Edition
     */

    public function testEdit(): void
    {
        $output = $this->crawl('/?action=edit&slug=title1_author1', 'POST', [
            'slug' => 'title1_author1',
            'title' => 'Title 1 Edited',
            'author' => 'author2',
            'type' => 'essay',
            'finished_at' => '2022-01-01',
            'summary' => 'new summary',
            'private_summary' => '1',
        ], true);

        $this->dom->loadStr($output);

        Assert::equals('List of read books 📚', $this->dom->findOne('h1')->innerText);
        Assert::equals('🗝 Title 1 Edited', $this->dom->findOne('li#title1_author1 .title-item')->innerText);
        Assert::equals('', $this->dom->findOne('li#title1_author1 .note-item')->innerText);
    }

    public function testEditKeepEditing(): void
    {
        $output = $this->crawl('/?action=edit&slug=title1_author1', 'POST', [
            'title' => 'Title 1 Edited',
            'author' => 'author2',
            'keep-editing' => 'OK+(keep+editing)',
        ], true);
        $this->dom->loadStr($output);

        Assert::equals('Title 1 Edited', $this->dom->findOne('input[name=title]')->getAttribute('value'));
    }

    public function testEditUnauthentified(): void
    {
        $output = $this->crawl('/?action=edit&slug=title1_author1', 'POST', [
            'slug' => 'title1_author1',
            'title' => 'Not logged',
            'author' => 'author2',
        ]);
        $this->dom->loadStr($output);

        Assert::equals('Title 1 Edited', $this->dom->findOne('li#title1_author1 .title-item')->innerText);
    }

    public function testDelete(): void
    {
        $output = $this->crawl('/?action=delete&slug=title2_author1', 'GET', [], true);
        $this->dom->loadStr($output);

        Assert::isNull($this->dom->findOne('li#title2_author1'));
    }

    public function testDeleteUnauthentified(): void
    {
        $output = $this->crawl('/?action=delete&slug=title3_author2');
        $this->dom->loadStr($output);

        Assert::exists($this->dom->findOne('li#title3_author2'));
    }
}
