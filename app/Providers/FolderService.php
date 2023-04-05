<?php

namespace App\Providers;

/**
 * Servis za sve vrste manipulacija sa slikama
 */
class FolderService extends BaseService {

    /**
     *
     * CREATE
     *
     */










    /**
     * Kreira folder
     * @param   string      $folder_destination Destinacija foldera
     * @return  bool                        Vraća true ako je folder kreiran ili u suprotno vraća error message
     */
    public static function createFolder($folder_destination) {
        $full_path = self::getPath() . '/' . $folder_destination;

        if (!is_dir($full_path)) {
            mkdir($full_path, 0755, true);
        }

        return true;
    }










    /**
     *
     * READ
     *
     */










    /**
     *
     * UPDATE
     *
     */










    /**
     * Menja ime foldera
     * @param   string      $old_name       Staro ime foldera
     * @param   string      $new_name       Novo ime foldera
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function updateFolder($old_name, $new_name) {
        self::moveFolder($old_name, $new_name);

        return true;
    }

    /**
     * Pomera folder na drugu lokaciju i sve u njemu što se nalazi
     * @param   string      $path_old       Stara putanja
     * @param   string      $path_new       Nova putanja
     * @param   boolean     $allow_spaces   Dozvolja razmake u imenu
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function moveFolder($path_old, $path_new, $allow_spaces = true) {
        $path = self::getPath();

        $full_path_old = $path . '/' . $path_old;
        $full_path_new = $path . '/' . $path_new;

        self::createFolder($path_new);

        if (file_exists($full_path_old)) {
            $contents = scandir($full_path_old);

            foreach ($contents as $node) {
                if ($node === '.' || $node === '..') {
                    continue;
                }

                if (is_dir($full_path_old . '/' . $node)) {
                    self::moveFolder($path_old . '/' . $node, $path_new . '/' . $node, $allow_spaces);
                } else {
                    copy($full_path_old . '/' . $node, $full_path_new . '/' . $node);
                }
            }
        }

        self::deleteFolder($path_old);

        return true;
    }

    /**
     * Premešta stvari iz jednog foldera u drugi
     * @param   string      $origin_folder          Folder iz koga se prebaciju
     * @param   string      $folder_destination     Folder u koji se prebacuje
     * @return  void
     */
    public static function moveFolderContent($folder_origin, $folder_destination) {
        $path = self::getPath();

        $full_path_origin       =   $path . '/' . $folder_origin;
        $full_path_destination  =   $path . '/' . $folder_destination;

        self::createFolder($folder_destination);

        $contents = scandir($full_path_origin);

        foreach ($contents as $node) {
            if ($node === '.' || $node === '..') {
                continue;
            }

            if (is_dir($full_path_origin . '/' . $node)) {
                self::moveFolderContent($folder_origin . '/' . $node, $folder_destination . '/' . $node);
            } else {
                copy($full_path_origin . '/' . $node, $full_path_destination . '/' . $node);
            }
        }
    }










    /**
     *
     * DELETE
     *
     */










    /**
     * Briše folder
     * @param   string          $path       Putanja do foldera
     * @return  bool|int                    true ako je sve uspešno prošlo inače vraća error_code
     */
    public static function deleteFolder($folder_path) {
        $full_path = self::getPath() . '/' . $folder_path;

        if (file_exists($full_path)) {
            self::deleteSubfolders($full_path);
        }

        return true;
    }

    /**
     * Briše podfoldere
     * @param    string   $path   Putanja do glavnog foldera gde se nalaze podfolderi
     */
    private static function deleteSubFolders($path) {
        if (is_dir($path)) {
            $contents = scandir($path);

            foreach ($contents as $node) {
                if ($node === '.' || $node === '..') {
                    continue;
                } else {
                    self::deleteSubfolders($path . '/' . $node);
                }
            }

            rmdir($path);
        } else {
            unlink($path);
        }
    }
}
