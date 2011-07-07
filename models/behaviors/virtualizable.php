<?php
    
    /**
    * Virtualizable Behavior for CakePHP 1.3
    * 
    * A Behavior to help manage virtualFields during finds - based largely on the ebook CakePHP 1.3 Application
    * Developlment by Mariano Iglesias - Chapter
    *
    * @author Abba James Bryant
    */

    class VirtualizableBehavior extends ModelBehavior
    {
        /**
        * settings indexed by model name.
        *
        * @var array
        * @access public
        */
        public $settings = array( );

        /**
        * Defaults
        *
        * virtualFields = an array of virtual model fields in the same format as the model property virtualFields
        *
        * @var array
        * @access protected
        */
        protected $_defaults = array(
            'virtualFields' => array( ),
        );

        /**
        * Virtual fields cache
        *
        * @var array
        * @access protected
        */
        protected $_backVirtualFields = array( );

        /**
        * Behavior setup
        *
        * Sets the calling models virtualFields property by merging the virtualFields config key with the Model::virtualFields property
        * 
        * @param Model Model
        * @param array $settings
        * @access public
        */
        public function setup( Model &$Model, $settings ){
            $this->settings[ $Model->alias ] = Set::merge( $this->_defaults, $settings );
            $Model->virtualFields = Set::merge( $this->settings[ $Model->alias ][ 'virtualFields'], $Model->virtualFields );
        }

        /**
        * beforeFind callback
        *
        * Sets the models virtualFields property based on the usage of a new model::find option 'virtualFields'
        * If the key is used the merged virtualFields are cached
        *
        * @param Model Model
        * @param array $query
        * @access public
        */
        public function beforeFind( &$Model, $query ){
            if( !empty( $Model->virtualFields )){
                $virtualFields = isset( $query[ 'virtualFields' ] ) ? $query[ 'virtualFields' ] : array_keys( $Model->virtualFields );
                if( $virtualFields !== true ){
                    $this->_backVirtualFields[ $Model->alias ] = $Model->virtualFields;
                    $Model->virtualFields = !empty( $virtualFields ) ? array_intersect_key( $Model->virtualFields, array_flip( (array) $virtualFields )) : array( );
                }
            }
            return $query;
        }

        /**
        * afterFind callback
        *
        * Restores any merged virtualFields cached in the beforeFind callback
        *
        * @param Model Model
        * @param array $result
        * @param $primary boolean
        * @access public
        */
        public function afterFind( &$Model, $results, $primary = false ){ 
            if( !empty( $this->_backVirtualFields[ $Model->alias ] )){
                $Model->virtualFields = $this->_backVirtualFields[ $Model->alias ];
            } 
        }
    }