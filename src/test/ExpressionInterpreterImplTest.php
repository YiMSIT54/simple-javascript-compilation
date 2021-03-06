<?php

use SimpleJavaScriptCompilation\ExpressionInterpreterImpl;
use PHPUnit\Framework\TestCase;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;

class ExpressionInterpreterImplTest extends TestCase
{
    public function readExpression_testCases(): array
    {
        return [
            [
                'true+true+true+true',
                new Context(),
                '4.000000000000000'
            ],
            [
                'true+true+true+false+true+true+true+true+false+true',
                new Context(),
                '8.000000000000000'
            ],
            [
                'true+""',
                new Context(),
                '"true"'
            ],
            [
                'false+""',
                new Context(),
                '"false"'
            ],
            [
                'undefined+""',
                new Context(),
                '"undefined"'
            ],
            [
                'true+3',
                new Context(),
                '4.000000000000000'
            ],
            [
                'false+3',
                new Context(),
                '3.000000000000000'
            ],
            [
                'true+null',
                new Context(),
                '1.000000000000000'
            ],
            [
                '5+2+6+10+true+undefined',
                new Context(),
                'NaN'
            ],
            [
                '5+2+6+10+true',
                new Context(),
                '24.000000000000000'
            ],
            [
                'true+true+false+(1+2+3+3)+true+false',
                new Context(),
                '12.000000000000000'
            ],
            [
                '1',
                new Context(),
                '1'
            ],
            [
                '(true+"")[1]',
                new Context(),
                '"r"'
            ],
            [
                '+("t1ue")[1]',
                new Context(),
                '1'
            ],
            [
                '+("11e20")',
                new Context(),
                '1.1E+21'
            ],
            [
                '(2+2+3+(4+5+6+(2+7+4)+4+5+(6+2+5)+2+6+2)+2+5+62+(24+20+39+(32+29+59+(38+39+(39+49+29)))))',
                new Context(),
                '533.000000000000000'
            ],
            [
                '!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[]',
                new Context(),
                '"8"'
            ],
            [
                '!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]',
                new Context(),
                '8.000000000000000'
            ],
            [
                '+!![]',
                new Context(),
                '1.000000000000000'
            ],
            [
                '!+[]+!![]+!![]+!![]+!![]+!![]+!![]',
                new Context(),
                '7.000000000000000'
            ],
            [
                '!+[]+!![]+!![]+!![]+!![]',
                new Context(),
                '5.000000000000000'
            ],
            [
                '(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(!+[]+!![])',
                new Context(),
                '"8175042"'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![]))/+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![]))',
                new Context(),
                '0.853970821858162'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]))/+((!+[]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]))',
                new Context(),
                '1.244435291841741'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![])+(+!![]))/+((!+[]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]))',
                new Context(),
                '2.377547078213877'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]))/+((!+[]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]))',
                new Context(),
                '1.938992291256846'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]))/+((!+[]+!![]+!![]+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![])+(+[]))',
                new Context(),
                '2.485375558062161'
            ],
            [
                'e("ZG9jdW1l")+(undefined+"")[1]+(true+"")[0]+(+(+!+[]+[+!+[]]+(!![]+[])[!+[]+!+[]+!+[]]+[!+[]+!+[]]+[+[]])+[])[+!+[]]+g(103)+(true+"")[3]+(true+"")[0]+"Element"+g(66)+(NaN+[Infinity])[10]+"Id("+g(107)+")."+e("aW5uZXJIVE1M")',
                (function(): Context {
                    $ctx = new Context();
                    $ctx->setCtxFunc('e', 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::atob');
                    $ctx->setCtxFunc('g', 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::stringFromCharCode');

                    return $ctx;
                })(),
                '"document.getElementById(k).innerHTML"'
            ],
            [
                '("")["italics"]()',
                new Context(),
                '"<i></i>"'
            ],
            [
                '("hey there")["italics"]()',
                new Context(),
                '"<i>hey there</i>"'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]))/+((!+[]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(+[])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]))',
                new Context(),
                '2.340355506431637'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(+[])+(+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]))/+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(+!![])+(+!![]))',
                new Context(),
                '1.015928758620422'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![])+(+!![])+(+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+!![]))/+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![])+(!+[]+!![]+!![]))',
                new Context(),
                '1.282825075025995'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]))/+((!+[]+!![]+!![]+!![]+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]))',
                new Context(),
                '1.952193129500363'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]))/+((!+[]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![])+(+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]))',
                new Context(),
                '1.685277914137290'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(!+[]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![]))/+((!+[]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![])+(+!![]))',
                new Context(),
                '1.405398085571678'
            ],
            [
                '"hey there, this is a test"["italics"]()',
                new Context(),
                '"<i>hey there, this is a test</i>"'
            ],
            [
                'test["italics"]()',
                (function(): Context {
                    $ctx = new Context();
                    $ctx->setCtxVar('test', (function(): CustomDataType {
                        $cbArgs =  [
                            "value"                 => '"hey there"',
                            "additionalOps"         => null,
                            "castsAndNegations"     => null,
                            "operator"              => null
                        ];

                        return new CustomString(new DataType($cbArgs));
                    })());

                    return $ctx;
                })(),
                '"<i>hey there</i>"'
            ],
            [
                '(true+"")[3]',
                new Context(),
                '"e"'
            ],
            [
                '"hello there".charCodeAt(3)',
                new Context(),
                '108'
            ],
            [
                '+((!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(+[])+(!+[]+!![]+!![]+!![])+(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![])+(+!![]))/+((!+[]+!![]+!![]+!![]+!![]+[])+(+[])+(+[])+(!+[]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]))',
                new Context(),
                '1.674142963698217'
            ],
            [
                '2+2+"hey there"',
                new Context(),
                '"4hey there"'
            ],
            [
                '2+2+"hey there"+(function(){ return " sup"; })()',
                new Context(),
                '"4hey there sup"'
            ],
            [
                'a+2+c+"hey there"+(function(){ return " sup"; })()',
                (function(): Context {
                    $ctx = new Context();
                    $ctx->setCtxVar('a', new CustomInteger(new DataType(['value' => '2'])));
                    $ctx->setCtxVar('c', new CustomString(new DataType(['value' => '"hello, "'])));

                    return $ctx;
                })(),
                '"4hello, hey there sup"'
            ]
        ];
    }

    /**
     * @dataProvider readExpression_testCases
     * @param string $expression
     * @param Context $ctx
     * @param string $expectedAnswer
     * @throws ErrorException
     */
    public function testReadExpression(string $expression, Context $ctx, string $expectedAnswer)
    {
        if (is_numeric($expectedAnswer) && strpos($expectedAnswer, '.') !== false) {
            $diff = bcsub($expectedAnswer, ExpressionInterpreterImpl::instance()->interpretExpression($expression, $ctx)->getDataType()->getValue(), 15);
            $this->assertLessThanOrEqual("0.000000000000001", $diff);
        } else {
            $this->assertEquals($expectedAnswer, ExpressionInterpreterImpl::instance()->interpretExpression($expression, $ctx)->getDataType()->getValue());
        }
    }
}
