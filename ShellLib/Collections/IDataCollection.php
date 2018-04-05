<?php
interface IDataCollection extends ICollection, Iterator, Countable, ArrayAccess
{
    function rewind();
    function current();
    function key();
    function next();
    function valid();
    function count();
    public function Keys();
    public function Add($item);

    public function Copy($items);
    public function OrderBy($field);
    public function Where($conditions);
    public function Take($count);
    public function First();
    public function Last();
    public function Any($conditions);

    public function offsetSet($offset, $value);
    public function offsetExists($offset);
    public function offsetUnset($offset);
    public function offsetGet($offset);
}