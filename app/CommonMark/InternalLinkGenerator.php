<?php

declare(strict_types=1);

namespace App\CommonMark;

use League\CommonMark\Extension\Mention\Generator\MentionGeneratorInterface;
use League\CommonMark\Extension\Mention\Mention;
use League\CommonMark\Node\Inline\AbstractInline;

class InternalLinkGenerator implements MentionGeneratorInterface
{
    public function generateMention(Mention $mention): ?AbstractInline
    {
        $identifier = $mention->getIdentifier();
        $identifier = str_replace('@', '', $identifier);
        $pieces = explode(':', $identifier);

        $mention->setUrl('/' . $pieces[0] . '/' . $pieces[1]);
        $mention->setLabel($pieces[2]);
        return $mention;
    }
}
