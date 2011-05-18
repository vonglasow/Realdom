<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2011, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace {

from('Hoa')

/**
 * \Hoa\Realdom
 */
-> import('Realdom.~')

/**
 * \Hoa\Realdom\String
 */
-> import('Realdom.String')

/**
 * \Hoa\Compiler\Llk
 */
-> import('Compiler.Llk')

/**
 * \Hoa\Compiler\Visitor\Realdom
 */
-> import('Compiler.Visitor.Realdom')

/**
 * \Hoa\File\Read
 */
-> import('File.Read');

}

namespace Hoa\Realdom {

/**
 * Class \Hoa\Realdom\Regex.
 *
 * Realistic domain: regex.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2011 Ivan Enderlin.
 * @license    New BSD License
 */

class Regex extends String {

    /**
     * Realistic domain name.
     *
     * @var \Hoa\Realdom string
     */
    protected $_name            = 'regex';

    /**
     * Regex.
     *
     * @var \Hoa\Realdom\Conststring object
     */
    protected $_regex           = null;

    /**
     * Regex compiler.
     *
     * @var \Hoa\Compiler\Llk object
     */
    protected static $_compiler = null;

    /**
     * Regex visitor that use realdom.
     *
     * @var \Hoa\Compiler\Visitor\Realdom object
     */
    protected static $_visitor  = null;



    /**
     * Construct a realistic domain.
     *
     * @access  public
     * @param   \Hoa\Realdom\Conststring  $regex    Regex.
     * @return  void
     */
    public function construct ( $regex = null, $foo = null, $bar = null ) {

        if(null === self::$_compiler)
            self::$_compiler = \Hoa\Compiler\Llk::load(
                new \Hoa\File\Read('hoa://Library/Regex/Grammar.pp')
            );

        if(null === self::$_visitor)
            self::$_visitor = new \Hoa\Compiler\Visitor\Realdom();

        $this->_regex = $regex;

        return;
    }

    /**
     * Predicate whether the sampled value belongs to the realistic domains.
     *
     * @access  public
     * @param   mixed  $q    Sampled value.
     * @return  boolean
     */
    public function predicate ( $q ) {

        return 0 !== preg_match(
            '#' . str_replace('#', '\#', $this->_regex->getConstantValue()) . '#',
            $q
        );
    }

    /**
     * Sample one new value.
     *
     * @access  protected
     * @param   \Hoa\Test\Sampler  $sampler    Sampler.
     * @return  mixed
     */
    protected function _sample ( \Hoa\Test\Sampler $sampler ) {

        $ast = self::$_compiler->parse($this->_regex->getConstantValue());

        return self::$_visitor->visit($ast, $sampler);
    }
}

}