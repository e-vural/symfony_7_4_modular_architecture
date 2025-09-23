<?= "<?php\n" ?>

namespace <?= $class_data->getNamespace(); ?>;

<?= $class_data->getUseStatements(); ?>


<?= $generator->generateRouteForControllerMethod($route_path, ""); ?>

<?php
// class adı (ör. Yeni3Controller)
$className = $class_data->getClassName();
// parent sınıfın kısa adı (ör. AbstractDenemeController) — generate() içinde bu değişken gönderiliyor
$parent = $parent_class ?? 'AbstractController';
?>

class <?= $className ?> extends <?= $parent ?>

{
<?= $generator->generateRouteForControllerMethod("/", $route_name,["GET"]); ?>
public function <?= $method_name ?>(): <?php if ($with_template) { ?>Response<?php } else { ?>JsonResponse<?php } ?>

{
<?php if ($with_template) { ?>
    return $this->render('<?= $template_name ?>', [
    'controller_name' => '<?= $class_data->getClassName() ?>',
    ]);
<?php } else { ?>
    return $this->json([
    'message' => 'Welcome to your new controller!',
    'path' => '<?= $relative_path; ?>',
    ]);
<?php } ?>
}


<?= $generator->generateRouteForControllerMethod("/", $route_name."_create",["POST"]); ?>
public function create(): <?php if ($with_template) { ?>Response<?php } else { ?>JsonResponse<?php } ?>

{
return $this->json([
'message' => 'Welcome to your new controller!',
'path' => '<?= $relative_path; ?>',
]);
}

<?= $generator->generateRouteForControllerMethod("/{id}", $route_name."_read",["GET"]); ?>
public function read(): <?php if ($with_template) { ?>Response<?php } else { ?>JsonResponse<?php } ?>

{
return $this->json([
'message' => 'Welcome to your new controller!',
'path' => '<?= $relative_path; ?>',
]);
}

<?= $generator->generateRouteForControllerMethod("/{id}", $route_name."_update",["PUT","PATCH"]); ?>
public function update(): <?php if ($with_template) { ?>Response<?php } else { ?>JsonResponse<?php } ?>

{
return $this->json([
'message' => 'Welcome to your new controller!',
'path' => '<?= $relative_path; ?>',
]);
}

<?= $generator->generateRouteForControllerMethod("/{id}", $route_name."_delete",["DELETE"]); ?>
public function delete(): <?php if ($with_template) { ?>Response<?php } else { ?>JsonResponse<?php } ?>

{
return $this->json([
'message' => 'Welcome to your new controller!',
'path' => '<?= $relative_path; ?>',
]);
}
}
