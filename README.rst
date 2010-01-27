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


