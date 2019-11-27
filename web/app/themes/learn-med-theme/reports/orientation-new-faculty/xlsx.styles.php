<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT

//$objReader = PHPExcel_IOFactory::createReader('Excel2007');
//$objTemplate = $objReader->load('template.xlsx');

//sd($objTemplate->getActiveSheet()->getStyle('E3'));

$styles_sheet_title = array(
  'font' => array(
    'bold' => true,
    'size' => 22
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array(
      'argb' => 'FFEAF1E0',
    ),
  ),
);

$styles_module_quiz_names = array(
  'font' => array(
    'size' => 14,
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array(
      'argb' => 'FFF2F2F2',
    ),
  ),
);

$styles_headers = array(
  'font' => array(
    'bold' => true,
    'size' => 14,
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array(
      'argb' => 'FFF2F2F2',
    ),
  ),
);

$styles_date = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
  ),
);

$styles_status_completed = array(
  'font' => array(
    'size' => 11,
    'bold' => true,
    'color' => array(
      'argb' => 'FF6AA14D',
    ),
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array(
      'argb' => 'FFD5F0C7',
    ),
  ),
);

$styles_status_started = array(
  'font' => array(
    'size' => 11,
    'bold' => true,
    'color' => array(
      'argb' => 'FF74740A',
    ),
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array(
      'argb' => 'FFFFFF79',
    ),
  ),
);

$styles_status_unknown = array(
  'font' => array(
    'size' => 11,
    'italic' => true,
    'color' => array(
      'argb' => 'FF999999',
    ),
  ),
);

$styles_no_data = array(
  'font' => array(
    'size' => 11,
    'color' => array(
      'argb' => 'FF721227',
    ),
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array(
      'argb' => 'FFD8B8BF',
    ),
  ),
);

$styles_no_answer = array(
  'font' => array(
    'size' => 11,
    'color' => array(
      'argb' => 'FF999999',
    ),
  )
);

$styles_correct_first_time = array(
  'font' => array(
    'size' => 11,
    'color' => array(
      'argb' => 'FF999999',
    ),
  ),
);


?>
