<?php

class GH_REPOS_GITHUB
{
    private $user;
    private $posts = [];
    function __construct($user)
    {
        $this->user = $user;
    }
    public function getRepos()
    {
        function sort_repos($a, $b)
        {
            $a_last_updated = strtotime($a->updated_at);
            $b_last_updated = strtotime($b->updated_at);
            return $b_last_updated - $a_last_updated;
        }



        $url = "https://api.github.com/users/gryphbecrazeh/repos";
        $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'User-Agent: Sandbox'
            ),
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC)
        ));
        $results = curl_exec($curl);
        if (!$results) {
            return die('Connection Failure');
        }
        curl_close($curl);
        $repos = json_decode($results);
        $sortedRepos = usort($repos, 'sort_repos');
        return $repos;
    }
}
