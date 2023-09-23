<?php

if (
    $payload['action'] == 'opened' &&
    isset($payload['pull_request'])
) {
    $PRNumber = $payload['pull_request']['number'];
    $user = $payload['pull_request']['user']['login'];
    $milestone = 1;

    // Assign the PR to the user who created it, and set the milestone to 1.
    api(
        $payload['pull_request']['issue_url'],
        json_encode(
            array(
                "milestone" => $milestone
            )
        )
    );
    api(
        $payload['pull_request']['issue_url'],
        json_encode(
            array(
                "assignees" => array($user)
            )
        )
    );

    // Send a message to the user who created the PR.
    api(
        $payload['pull_request']['comments_url'],
        json_encode(
            array(
                "body" => "Thanks for submitting a pull request!\r\nWe will review it as soon as possible."
            )
        ),
        'POST'
    );

    discord("PR [#$PRNumber]({$payload['pull_request']['html_url']}) assigned to $user and set to milestone [#$milestone](https://github.com/AuroraEditor/AuroraEditor/milestone/$milestone).");

    // Enable auto merge
    // TODO: Make this working.

    $AEdidRun = [true, "pr_assign_user", "PR #$PRNumber assigned to $user."];
}
