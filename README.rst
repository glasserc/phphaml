This is a fork of the `PHPHaml project
<http://phphaml.sourceforge.net/>`_. I thought it would be more
effective to track patches using github rather than just emailing them
to upstream.

So far the patches included here are:

* "magic else" -- PHPHaml allows templates like the following::

    - if(false)
      Hi
    - else
      Bye

  However, upstream breaks on this "else" because the regex being used
  to look for this case doesn't match the code that generates it.

* dashes in id names: fix a bug where ids like ``#abc-def`` weren't
  recognized as XHTML ids.

* arrays as hashes: allow passing hashes to the element attribute tag,
  like this::

      - $ary = array('name' => 'myname', 'href' => "#myname")
      %a{ $ary, :rel => 'link' }

  The behavior is not exactly like Ruby HAML, because the attributes
  specified in the element itself will all come at the end, but it's a
  start.

* pipe handling: Ruby HAML treats this as a special case::

      Some text
      |
      More text

  If a pipe is the first character on a line, not counting
  indentation, it does not count as a line break. Since HTML designers
  tend to use the pipe character as a separator, it's important to get
  this case right.

* commas: Ruby HAML doesn't do much processing on attribute elements,
  which allows you to do things like this::

      %a{ :href=>"a,b,c", :target => "_blank" }

  PHPHaml has to do some processing here to transform ``:href`` into
  ``"href"`` and put everything into an ``array()``. To do this, it
  splits on commas, which is obviously problematic if one of your
  values has a comma in it.

  The patch to fix this relies on the similarity between
  attribute-hash syntax in HAML and an argument array, and simply runs
  a regex to map :symbol to "symbol". Then we don't have to split on
  commas in order to split up arguments -- the PHP parser will do it
  for us.

  This might give you pause, because of the "arrays as hashes of
  attributes" feature above. But this works, due to a PHP oddity --
  arrays can have both numeric and non-numeric keys, and the syntax
  expressly allows you to mix them, as follows::

      $a = array('foo' => 'bar', 'baz');

  ($a['foo'] is 'bar', and $a[0] is 'baz'.) Using this behavior, we can
  still rely on array literal syntax::

      %a{ :href=>$key, $arguments }

  becomes::

      <a <?php $this->writeAttributes(array('href'=>$key, $value)); ?>>

  And the writeAttributes method is smart enough to recognize that
  $value has an integer key, so render it recursively.

* reentrancy: previously PHPHaml had a giant static $aVariables array,
  which was modified by calling assign() on *any* HamlParser
  object. This sucks if you have multiple HamlParsers, want to render
  HAML recursively, etc. Turning that into an object-local variable
  was pretty trivial. Additionally, we found it convenient to pass a
  $context array to render(), which is used in addition to
  $this->aVariables, to populate the scope of the HAML code.

* class design: PHPHaml upstream has all HAML processing logic in one
  huge HamlParser class. It turns out you can decompose this at least
  a little bit into a HamlLine class, which corresponds roughly to a
  node in a parse tree, with one line of HAML to compile and some
  number of children, and a HamlParser that subclasses HamlLine and
  adds some whole-file code. This is a little easier to work with.

* whitespace eaters. HAML defines two element modifiers that eat
  whitespace: %foo> and %foo<. PHPHaml upstream supports neither; we
  only support the outside-the-element eater (%foo>).
