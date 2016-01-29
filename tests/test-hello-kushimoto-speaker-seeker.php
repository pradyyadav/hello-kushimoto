<?php

require_once 'mock/class-speaker-mock.php';

class Dummy_Speaker_Mock {
	public function whoami() {
		// TODO: Implement whoami() method.
		return 'mock';
	}

	public function say() {
		// TODO: Implement say() method.
		return "I'm Dummy Speaker.";
	}
}

class Test_Hello_Kushimoto_Speaker_Seeker extends WP_UnitTestCase {


	/** @var Hello_Kushimoto_Option_Manager */
	private $option_manager;
	/** @var  Hello_Kushimoto_Speaker_Seeker */
	private $speaker_seeker;

	public function setUp() {
		parent::setUp(); // TODO: Change the autogenerated stub
		$this->option_manager = new Hello_Kushimoto_Option_Manager();
		$this->speaker_seeker = new Hello_Kushimoto_Speaker_Seeker( $this->option_manager );
	}


	public function test_search() {

		$class_names = $this->speaker_seeker->search_classes();
		$this->assertTrue( is_array( $class_names ) );
		$this->assertNotEmpty( $class_names );

		foreach ( $class_names as $class_name ) {
			$this->assertTrue( class_exists( $class_name, true ) );
		}
	}

	public function test_convert_name() {

		$method = new ReflectionMethod( get_class( $this->speaker_seeker ), 'convert_to_class_name' );
		$method->setAccessible( true );
		$expected = 'Miyasan';

		$actual = $method->invoke( $this->speaker_seeker,  HELLO_KUSHIMOTO_DIR . '/src/speaker/concrete/class-miyasan.php' );
		$this->assertEquals( $expected, $actual );
	}

	public function test_create_speaker_return_speaker() {

		$method = new ReflectionMethod( get_class( $this->speaker_seeker ), 'create_speaker' );
		$method->setAccessible( true );

		$speaker_mock = $method->invoke( $this->speaker_seeker, 'Speaker_Mock' );

		$this->assertTrue( $speaker_mock instanceof Speaker_Mock );
		$speaker_mock = $method->invoke( $this->speaker_seeker, 'Dummy_Speaker_Mock' );
		$this->assertNull( $speaker_mock );
	}

	public function test_get_all_speakers() {
		$speakers = $this->speaker_seeker->get_all_speakers();
		$this->assertNotEmpty( $speakers );
		$files = glob( HELLO_KUSHIMOTO_DIR . '/src/speaker/concrete/*.php' );
		$this->assertEquals( count( $files ), count( $speakers ) );

	}
}
