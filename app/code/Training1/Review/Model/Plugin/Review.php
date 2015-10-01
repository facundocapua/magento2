<?php
/**
 * Class Review
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training1\Review\Model\Plugin;

class Review
{
    /**
     * Validate the Nickname does not contain dashes
     *
     * @param \Magento\Review\Model\Review $review
     * @param                              $result
     *
     * @return array
     * @throws \Exception
     * @throws \Zend_Validate_Exception
     */
    public function afterValidate(\Magento\Review\Model\Review $review, $result)
    {
        if (\Zend_Validate::is($review->getNickname(), 'Regex', ['pattern' => '/_/'])) {
            if(!is_array($result)){
                $result = [];
            }
            $result[] = __('Nickname can not contain dashes.');
        }

        return $result;
    }
}