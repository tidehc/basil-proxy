<?php
/*************************************************************************************
 * basil-proxy: A proxy solution for Digital Signage SMIL Player
 * Copyright (C) 2018 Nikolaos Sagiadinos <ns@smil-control.com>
 * This file is part of the basil-proxy source code
 *
 * This program is free software: you can redistribute it and/or  modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *************************************************************************************/


use Basil\controller\SmilIndexController;
use PHPUnit\Framework\TestCase;

class SmilIndexControllerTest extends TestCase
{
	/**
	 * @var \Basil\model\PlayerModel
	 */
	protected $PlayerModelMock;
	protected $IndexModelMock;
	protected $ConfigMock;
	protected $CurlMock;

	/**
	 *  unset all Mocks
	 */
	protected function tearDown()
	{
		unset($this->PlayerModelMock);
		unset($this->IndexModelMock);
		unset($this->ConfigMock);
		unset($this->CurlMock);
	}

	/**
	 * @group units
	 */
	public function testReadDownloadedIndex()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$expected   = 'This is the downloaded content';
		$this->CurlMock->expects($this->once())->method('getResponseBody')->willReturn($expected);
		$returned = $Controller->readDownloadedIndex();
		$this->assertEquals($expected, $returned);

	}

	/**
	 * @group units
	 */
	public function testRequestIndexForRegisteredPlayer()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->CurlMock->expects($this->once())->method('clearHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setSplitHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setRequestMethodHead')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setRequestMethodget')->willReturn($this->CurlMock);

		$this->CurlMock->expects($this->once())->method('getHttpCode')->willReturn(200);
		$Controller->requestIndexForRegisteredPlayer('an_uuid');
		$this->assertTrue($Controller->isNewIndex());
	}

	/**
	 * @group units
	 */
	public function testRequestIndexForRegisteredPlayerUnequal200()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->CurlMock->expects($this->once())->method('clearHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setSplitHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setRequestMethodHead')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->never())->method('setRequestMethodGet')->willReturn($this->CurlMock);

		$this->CurlMock->expects($this->once())->method('getHttpCode')->willReturn(500);
		$Controller->requestIndexForRegisteredPlayer('an_uuid');
		$this->assertFalse($Controller->isNewIndex());
	}


	// ===================== helper methods =============================================================================

	/**
	 * @return SmilIndexController
	 */
	protected function initMockAllConstructorInjections()
	{
		$this->PlayerModelMock = $this->createMock('Basil\model\PlayerModel');
		$this->IndexModelMock  = $this->createMock('Basil\model\IndexModel');
		$this->ConfigMock      = $this->createMock('Basil\helper\Configuration');
		$this->CurlMock        = $this->createMock('Thymian\framework\Curl');

		return new SmilIndexController($this->PlayerModelMock, $this->IndexModelMock, $this->ConfigMock, $this->CurlMock);

	}

}
