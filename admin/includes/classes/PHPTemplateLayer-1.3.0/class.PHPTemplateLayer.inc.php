<?php

/*****************************************************************************\
| http://www.sitecenter.com                                                   |
| Copyright (c) 2020 SiteCenter.com <sales@sitecenter.com>                    |
| All rights reserved.                                                        |
| See COPYING.txt for license details.
                                        |
\*****************************************************************************/

	class PHPTemplateLayer
	{
	public $index = Array();
	public $content = Array();        
	public $currentBlock;
	public $globalVariables = Array();
	public $prepared = false;
	public $templateCounter = 0;
	public $parent = Array();
	public $defBlock = Array();
	public $definedBlocks = Array();
	public $output = '';

///*****************************************************************************///

		private function __parse($templateVariable, $blockName, $initial)
		{
		$codeRow = $initial["codeRow"];
		$varRow = $initial["varRow"];
		$index = $initial["index"];

			while($index < $this->{$templateVariable}["size"])
			{
				if(preg_match('/<!--[ ]?(START|END) BLOCK : (.+)-->/', $this->{$templateVariable}["content"][$index], $regs))
				{
				$regs[2] = trim($regs[2]);

					if($regs[2] == $blockName)
					{
					break;
					}
					else
					{
					$this->defBlock[$regs[2]] = Array();
					$this->defBlock[$blockName]["_B:". $regs[2]] = '';
					$this->index[$regs[2]] = 0;
 					$this->parent[$regs[2]] = $blockName;
					$index++;
					$initial["varRow"] = 0;
					$initial["codeRow"] = 0;
					$initial["index"] = $index;
					$initial = $this->__parse($templateVariable, $regs[2], $initial);
					$index = $initial["index"];
					}
				}
				else
				{
				$sstr = explode('*{', $this->{$templateVariable}["content"][$index]);
				reset($sstr);

					if (current($sstr) != '')
					{
					$this->defBlock[$blockName]["_C:$codeRow"] = current($sstr);
					$codeRow++;
					}

					while (next($sstr))
					{
					$pos = strpos(current($sstr), "}*");

						if (($pos !== false) && ($pos > 0))
						{
						$strlength = strlen(current($sstr));
						$variableName = substr(current($sstr), 0, $pos);

							if (strstr($variableName, ' '))
							{
							$this->defBlock[$blockName]["_C:$codeRow"] = '*{'. current($sstr);
							$codeRow++;
							}
							else
							{
							$this->defBlock[$blockName]["_V:$varRow"] = $variableName;
							$varRow++;

								if(($pos + 1) != $strlength)
								{
								$this->defBlock[$blockName]["_C:$codeRow"] = substr(current($sstr), ($pos + 2), ($strlength - ($pos + 2)));
								$codeRow++;
								}
							}
						}
						else
						{
						$this->defBlock[$blockName]["_C:$codeRow"] = '*{'. current($sstr);
						$codeRow++;
						}
					}
				}

			$index++;
			}

		$initial["varRow"] = $varRow;
		$initial["codeRow"] = $codeRow;
		$initial["index"] = $index;
		return $initial;
		}

///*****************************************************************************///

		private function __assign($variableName, $value)
		{
			if(sizeof($regs = explode('.', $variableName)) == 2)
			{
			$indexBlockName = $regs[0] .'_'. $this->index[$regs[0]];	
			$lastItem = sizeof($this->content[$indexBlockName]);

			$lastItem > 1 ? $lastItem-- : $lastItem = 0;
			$block = &$this->content[$indexBlockName][$lastItem];
			$variableName = $regs[1];
			}
			else
			{
			$block = &$this->currentBlock;
			}

		$block["_V:$variableName"] = $value;
		}

///*****************************************************************************///

		private function __display($blockName,$parsetype)
		{
		$numrows = sizeof($this->content[$blockName]);

			for($i=0; $i < $numrows; $i++)
			{
			$defblockName = $this->content[$blockName][$i][0];

				for(reset($this->defBlock[$defblockName]);  $key = key($this->defBlock[$defblockName]); next($this->defBlock[$defblockName])) 
				{
					if ($key[1] == 'C')
					{
					$this->output .= $this->defBlock[$defblockName][$key];
					}
					elseif ($key[1] == 'V')
					{
                        $defValue = $this->defBlock[$defblockName][$key];

						if(!isset($this->content[$blockName][$i]["_V:". $defValue]))
						{
							if(isset($this->globalVariables[$defValue]))
							{
							$this->output .= $this->globalVariables[$defValue];
							}
							else
							{
								if($parsetype == "PARTIAL")
								{
								$this->output .= '*{'.$defValue.'}*';
								}
							}
						}
						else
						{
						$this->output .= $this->content[$blockName][$i]["_V:". $defValue];
						}
					}
					elseif ($key[1] == 'B')
					{
						if(isset($this->content[$blockName][$i][$key]))
						{
						$this->__display($this->content[$blockName][$i][$key],$parsetype);
						}
					}
				}
			}
		}

///*****************************************************************************///

		public function prepare($templateFile = '',$templateType = '')
		{
 		$this->defBlock['_ROOT'] = Array();
		$templateVariable = 'templateRawContent'. $this->templateCounter;

			if($templateType == "VARIABLE") 
			{
			$content = $templateFile;

				if(!$content)
				{
				return "FALSE";
				die();
				}
			}
			else
			{
			$content = @file($templateFile);

				if(!$content)
				{
				die('Error. Could not open template.');
				}

			$content = implode('', $content);
			}

		$content = preg_replace("@[\n]+@", "\r\n", $content);

		$content = preg_replace(array('/[\t ]*<!--[ ]?START BLOCK : (.+)-->/', '/[\t ]*<!--[ ]?END BLOCK : (.+)-->/'),array("<!-- bb : $1-->\n$0", "$0\n<!-- eb : $1-->"), $content);
		$content_split = preg_split('/(<!--[ ]?START[ ]?NOPARSE[ ]?-->.+?<!--[ ]?END[ ]?NOPARSE[ ]?-->)/si', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

			for($i=0; $i < count($content_split); $i++)
			{
				if (preg_match('/<!--[ ]?START[ ]?NOPARSE/', $content_split[$i])) 
				{
				$content_split[$i] = preg_replace(array('/\*{/', '/}\*/'), array("&times;&lt;&gt;&divide;", "&divide;&lt;&gt;&times;"), $content_split[$i]);
				$content_split[$i] = preg_replace(array('/<!--[ ]?START BLOCK/', '/<!--[ ]?END BLOCK/'), array("<!-- &lt;&gt;TART &lt;&gt;ROUP", "<!-- &lt;&gt;ND &lt;&gt;ROUP"), $content_split[$i]);
				} 
			}

		$content = implode ($content_split); 

		$this->{$templateVariable}["content"] = explode("\n", $content);
	
			foreach($this->{$templateVariable}["content"] as $line => $content)
			{
			$this->{$templateVariable}["content"][$line] = preg_replace('/[\r\n]+$/', "\n", $content);
			}

		$this->{$templateVariable}["size"] = sizeof($this->{$templateVariable}["content"]);
		$this->templateCounter++;
		$initial["varRow"] = 0;
		$initial["codeRow"] = 0;
		$initial["index"] = 0;

		$this->__parse($templateVariable, '_ROOT', $initial);
		for($i=0; $i <= $this->templateCounter; $i++) unset($this->{'templateRawContent'. $i});
		$this->prepared = true;
		$this->index['_ROOT'] = 0;
		$this->content['_ROOT_0'][0] = Array('_ROOT');
		$this->currentBlock = &$this->content['_ROOT_0'][0];
		}

///*****************************************************************************///

		public function block($blockName, $single = false)
		{
		$this->definedBlocks[$blockName] = $blockName;
		$parent = &$this->content[$this->parent[$blockName] .'_'. $this->index[$this->parent[$blockName]]];
		if(is_array($parent)){
			$lastItem = sizeof($parent);	
		}else{
			$lastItem = -1;
		}
		$lastItem > 1 ? $lastItem-- : $lastItem = 0;
		$indexBlockName = $blockName .'_'. $this->index[$blockName];

			if(!isset($parent[$lastItem]["_B:$blockName"]))
			{
			$this->index[$blockName] += 1;
			$indexBlockName = $blockName .'_'. $this->index[$blockName];				

				if(!isset($this->content[$indexBlockName]))
				{
				$this->content[$indexBlockName] = Array();
				}

			$parent[$lastItem]["_B:$blockName"] = $indexBlockName;
			}

		$blockSize = $single ? 0 : sizeof($this->content[$indexBlockName]); // solves multiple blocks from being added where it's not needed
		$this->content[$indexBlockName][$blockSize] = Array($blockName);
		$this->currentBlock = &$this->content[$indexBlockName][$blockSize];
		}

///*****************************************************************************///

		public function assignGlobal($variableName, $value = '')
		{
			if(is_array($variableName))
			{
				foreach($variableName as $var => $value)
				{
				$this->globalVariables[$var] = $value;
				}
			}
			else
			{
			$this->globalVariables[$variableName] = $value;
			}
		}

///*****************************************************************************///

		public function assign($variableName, $value = '')
		{
			if(is_array($variableName))
			{
				foreach($variableName as $var => $value)
				{
				$this->__assign($var, $value);
				}
			}
			else
			{
			$this->__assign($variableName, $value);
			}
		}

///*****************************************************************************///

		public function display($outputformat = '',$parsetype ='',$minify = 'NONE')
		{
			if($this->prepared)
			{
				if($parsetype == "PARTIAL")
				{
					foreach($this->defBlock as $blockName => $temp)
					{
						if($blockName != '_ROOT' && !isset($this->definedBlocks[$blockName]))
						{
						$this->block($blockName);
						unset($this->definedBlocks[$blockName]);
						}
					}
				}

			$this->__display('_ROOT_0',$parsetype);

			foreach($this->defBlock as $blockName => $temp)
			{
				if($blockName != '_ROOT' && !isset($this->definedBlocks[$blockName]) && $parsetype == "PARTIAL")
				{
				$this->output = $this->__replaceAllButFirst("/<!-- bb : $blockName -->/s", "\n<!-- START BLOCK : $blockName -->\n", $this->output );
				$this->output = $this->__replaceAllButFirst("/<!-- eb : $blockName -->/s", "\n<!-- END BLOCK : $blockName -->\n", $this->output);
				}
			}

			$this->output = preg_replace(array("/&times;&lt;&gt;&divide;/", "/&divide;&lt;&gt;&times;/"), array('*{', '}*') , $this->output);
			$this->output = preg_replace(array("/<!-- &lt;&gt;TART &lt;&gt;ROUP/", "/<!-- &lt;&gt;ND &lt;&gt;ROUP/"), array('<!-- START BLOCK', '<!-- END BLOCK') , $this->output);
			$this->output = preg_replace("/<!-- (bb|eb) : ([^<]+)-->/", '', $this->output);
			$this->output = preg_replace("/<!--[ ]?(START|END)[ ]?NOPARSE[ ]?-->/", '', $this->output);

			if ($parsetype != "PARTIAL" && $minify == 'MINIFY')
			{
				$parts = preg_split('/(<!--[ ]?START[ ]?NOPROCESS[ ]?-->.+?<!--[ ]?END[ ]?NOPROCESS[ ]?-->)/si', $this->output, -1, PREG_SPLIT_DELIM_CAPTURE);

				$parts_processed = '';

				foreach ( $parts as $part )
				{
					if ( ! preg_match( '/<!--[ ]?START[ ]?NOPROCESS/', $part ) )
						$parts_processed .= $this->__minifyHTML( $part );
					else
						$parts_processed .= preg_replace( '/<!--(.*)-->/Uis', '', $part );
				}

				$this->output = $parts_processed;
			}

			if($outputformat != "VARIABLE")
			{
				print($this->output);
			}

			return $this->output;	
			}
			else
			{
				if($outputformat != "VARIABLE")
				{
				print('Error. Could not open template.');
				}
				else
				{
				return "FALSE";
				}
			}
		}

		private function __replaceAllButFirst($pattern, $replacement, $subject)
		{

			return preg_replace_callback($pattern,

				function($matches) use ($replacement, $subject) {
					static $s;
					$s++;
					return ($s > 1) ? $matches[0] : $replacement;
				},

				$subject
			);
		}

		private function __minifyHTML( $html )
		{
			$replace = array(
				//remove tabs before and after HTML tags
				'/\>[^\S ]+/s'   => '>',
				'/[^\S ]+\</s'   => '<',
				//shorten multiple whitespace sequences; keep new-line characters because they matter in JS!!!
				'/([\t ])+/s'  => ' ',
				//remove leading and trailing spaces
				'/^([\t ])+/m' => '',
				'/([\t ])+$/m' => '',
				// remove JS line comments (simple only); do NOT remove lines containing URL (e.g. 'src="http://server.com/"')!!!
				'~//[a-zA-Z0-9 !<\[\]\(\)\>]+[\r\n\t ]+~m' => '',
				//remove empty lines (sequence of line-end and white-space characters)
				'/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
				//remove empty lines (between HTML tags)
				'/\>[\r\n\t ]+\</s'    => '><',
				//remove line breaks
				'/[\r\n]+/s'    => '',
				//remove "empty" lines containing only JS's block end character; join with next line (e.g. "}\n}\n</script>" --> "}}</script>"
				'/}[\r\n\t ]+/s'  => '}',
				'/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
				//remove new-line after JS's function or condition start; join with next line
				'/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
				'/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
				//remove new-line after JS's line end (only most obvious and safe cases)
				'/\),[\r\n\t ]+/s'  => '),',
				//remove quotes from HTML attributes that does not contain spaces; keep quotes around URLs!
				'~([\r\n\t ])?([a-zA-Z0-9]+)="([a-zA-Z0-9_/\\-]+)"([\r\n\t ])?~s' => '$1$2=$3$4', //$1 and $4 insert first white-space character found before/after attribute
				//remove html comments
				'/<!--(.*)-->/Uis' => '',
				// add space between self closing tag end and last string if the string does not have special characters like quotes ("). Useful when class attribute is defined last
				'~([a-zA-Z0-9_/\\-]+)\/\>~s' => '$1 />',
				'/\/\*(.*)\*\//Uis' => ''
			);

			$html = preg_replace(array_keys($replace), array_values($replace), $html);

			return $html;
		}
	}
?>