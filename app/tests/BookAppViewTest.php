<?php

namespace App\Tests;

use App\Tests\Dom\Dom;
use App\Tests\Util\Assert;
use App\Tests\Util\FunctionalTestCase;

class BookAppViewTest extends FunctionalTestCase
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
        Assert::equals('title2_author1', $this->dom->find('li.books-list__item')[4]->getAttribute('id'));
        Assert::equals('title1_author1', $this->dom->find('li.books-list__item')[5]->getAttribute('id'));
    }

    public function testSee(): void
    {
        $this->dom->loadStr($this->crawl('/?action=show&slug=title1_author1'));
        Assert::equals('📖 Title 1', $this->dom->findOne('h1')->innerHtml);
    }

    public function testEditForm(): void
    {
        $output = $this->crawl('/?action=edit&slug=title1_author1', 'GET', [], true);
        $this->dom->loadStr($output);

        Assert::equals('Title 1', $this->dom->findOne('input[name=title]')->getAttribute('value'));
        Assert::equals('author1', $this->dom->findOne('input[name=author]')->getAttribute('value'));
        Assert::equals('2022-01-01', $this->dom->findOne('input[name=finished_at]')->getAttribute('value'));
        Assert::equals('essay', $this->dom->findOne('select[name=type] option[selected=true]')->getAttribute('value'));
        Assert::equals('0', $this->dom->findOne('select[name=note] option[selected=true]')->getAttribute('value'));
        Assert::equals('* (mh)', $this->dom->findOne('select[name=note] option[selected=true]')->innerText);
        Assert::equals('summary', $this->dom->findOne('textarea[name=summary]')->innerText);
        Assert::true($this->dom->findOne('input[name=private_book]')->hasAttribute('checked'));
        Assert::false($this->dom->findOne('input[name=private_summary]')->hasAttribute('checked'));
    }
}
